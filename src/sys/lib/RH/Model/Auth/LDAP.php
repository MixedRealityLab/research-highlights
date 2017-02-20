<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2016 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model\Auth;

/**
 * Model for login handling authentication against an LDAP server.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class LDAP extends AbstractAuth implements \RH\Singleton
{
    /** @var mixed LDAP connection resource. */
    private $ldap = null;

    /** @var Cache[] Cached data from the model. */
    private $cache = [];

    /** @var string Cached LDAP auth data. */
    const AUTH_CACHE = 'auth-ldap-%s.cache';

    /**
     * Connect to the LDAP server. A connection is only performed once, subsequent calls will fail
     * unless `$this->ldap` is set to `null`.
     *
     * @throws     \RH\Error\Configuration  If there was an error connecting to the LDAP server.
     */
    private function connect()
    {
        if (!is_null($this->ldap)) {
            return;
        }

        $this->ldap = \ldap_connect(LDAP_HOST, LDAP_PORT);
        if ($this->ldap === false) {
            throw new \RH\Error\Configuration('LDAP host/port error.');
        }

        if (!ldap_set_option($this->ldap, LDAP_OPT_PROTOCOL_VERSION, LDAP_VERSION)) {
            throw new \RH\Error\Configuration('LDAP version error.');
        }

        if (LDAP_TLS && !ldap_start_tls($this->ldap)) {
            throw new \RH\Error\Configuration('LDAP STARTTLS error.');
        }
    }

    /**
     * Gets the user information from the LDAP server.
     *
     * @param string $username User to retrieve information for.
     * @throws     \RH\Error\Configuration  Error retreiving information from the LDAP server.
     *
     * @return     Cache|boolean            Cached user information, or `false.`
     */
    public function getUser($username)
    {
        $this->connect();
        if (isset($this->cache[$username])) {
            return $this->cache[$username];
        }

        $cache = new Cache();
        $cache->setCache(CACHE_USER, \sprintf(self::AUTH_CACHE, $username));

        if ($cache->hasCache()) {
            $cache->loadCache();
            $this->cache[$username] = $cache;
            return $cache;
        }

        $cache->username = $username;

        $filter = \sprintf(LDAP_SEARCH, $username);
        $fetch = [LDAP_FIELD_SURNAME, LDAP_FIELD_GIVENNAME, LDAP_FIELD_EMAIL, 'dn'];
        $result_ident = \ldap_search($this->ldap, LDAP_BASE,  $filter, $fetch);

        if ($result_ident === false) {
            throw new \RH\Error\Configuration('LDAP search error.');
        }

        $entries = \ldap_get_entries($this->ldap, $result_ident);
        if ($entries['count'] == 0) {
            if (LDAP_BACKUP_HASH) {
                $cache->hash = true;
                $this->cache[$username] = $cache;
                $cache->saveCache();
            } else {
                return false;
            }
        } else {
            $fSurname = strtolower(LDAP_FIELD_SURNAME);
            if (!empty($fSurname) && $entries[0][$fSurname]['count'] > 0) {
                $cache->surname = $entries[0][$fSurname][0];
            }

            $fGivenName = strtolower(LDAP_FIELD_GIVENNAME);
            if (!empty($fGivenName) && $entries[0][$fGivenName]['count'] > 0) {
                $cache->firstName = $entries[0][$fGivenName][0];
            }

            $fEmail = strtolower(LDAP_FIELD_EMAIL);
            if (!empty($fEmail) && isset($entries[0][$fEmail]) && $entries[0][$fEmail]['count'] > 0) {
                $cache->email = $entries[0][$fEmail][0];
            }

            $cache->hash = false;
            $cache->dn = $entries[0]['dn'];
            $dn = $entries[0]['dn'];

            $this->cache[$username] = $cache;
            $cache->saveCache();
        }

        return $cache;
    }

    /**
     * Test user credentials.
     *
     * @param string $username Username to test with.
     * @param string $password Password to use to test with.
     * @return boolean
     * @throws     \RH\Error\Configuration  If there was an error connecting to the LDAP server.
     */
    public function test($username, $password)
    {
        $cache = $this->getUser($username);
     
        if ($cache->hash) {
            $mAuth = \I::RH_Model_Auth_Hash();
            return $mAuth->test($username, $password);
        } else {
            return \ldap_bind($this->ldap, $cache->dn, $password);
        }
    }

    /**
     * Determine which if a value can be retrieved from the authentication model.
     *
     * @param string $key Column to determine if can be retrieved from the authentication model.
     * @return boolean
     */
    public function provides($key)
    {
        $provides = [
            'password' => false,
            'firstName' => true,
            'surname' => true,
            'email' => true
        ];
        
        $cache = $this->getUser($username);
        if ($key === 'password' && $cache->hash) {
            $mAuthHash = \I::RH_Model_Auth_Hash();
            return true;
        } 

        if (isset($provides[$key])) {
            return $provides[$key];
        }

        return false;
    }

    /**
     * Retrieve a value from from the authentication model.
     *
     * @param string $key Column to retrieve from the authentication model.
     * @param array $data Data necessary for retrieval.
     * @return string Value for the column, or `null`
     */
    public function get($key, array $data)
    {
        $cache = $this->getUser($username);

        switch ($key) {
            case 'password':
                if ($cache->hash) {
                    $mAuthHash = \I::RH_Model_Auth_Hash();
                    return $mAuthHash->test($username, $password);
                } else {
                    return PASSWORD_STATEMENT;
                }

            case 'surname':
            case 'firstName':
            case 'email':
                return $cache->$key;

            default:
                return null;
        }
    }


}