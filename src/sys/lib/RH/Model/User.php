<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * A user within the system.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class User extends AbstractModel
{
    /** @var \RH\Model\Auth\AbstractAuth Authentication model */
    private $mAuth = null;

    /**
     * Get the user's password
     *
     * @return string Password of the user
     */
    public function getAuthenticationModel()
    {
        if ($this->mAuth == null) {
            $class = 'RH_Model_Auth_'. AUTH;
            $this->mAuth = \I::$class();
        }

        return $this->mAuth;
    }

    /**
     * Get the user's password, or equivalent message about the password. You shouldn't use this to test authentication
     * as not all Auth models will return a password.
     *
     * @return string Password of the user, or the equivalent message.
     */
    public function getPassword()
    {
        return $this->getAuthenticationModel()->get('password', [$this->username]);
    }

    /**
     * Get a field from the authentication model. If the authentication model does not provide the information, `null` 
     * is returned. 
     *
     * @param string $key Value to retrieve from the authentication model.
     * @return string Value from the authentiation model, or `null`.
     */
    public function getFromAuthModel($key)
    {
        return $this->getAuthenticationModel()->get($key, [$this->username]);
    }

    /**
     * Get the user's password
     *
     * @param string $password Password to test.
     * @return boolean
     */
    public function authenticate($password)
    {
        return $this->getAuthenticationModel()->test($this->username, $password);
    }

    /**
     * List of possible substitutions.
     *
     * @param bool $includePassword Include the password in the substitutions
     * @return string[] List of possible substitutions
     */
    public static function substsKeys($includePassword = false)
    {
        $k = array ('<wordCount>', '<fundingStatment>', '<imgDir>');
        if ($includePassword) {
            $k[] = 'password';
        }

        $oFileReader = \I::RH_File_Reader();
        $header = $oFileReader->readHeader(DIR_USR . \RH\User::USER_FILE);
        $ret = \array_merge($k, array_map(function ($col) {
            return '<' . $col->name .'>';
        }, $header->toArray()));

        \sort($ret, SORT_STRING|SORT_NATURAL);
        return $ret;
    }

    /**
     * Scan text for keywords that can be replaced.
     *
     * @param string $input Input to be scanned
     * @param bool $includePassword Include the password in the substitutions
     * @return string Output with the substitutions made
     */
    public function makeSubsts($input, $includePassword = false)
    {
        $k = array ('imgDir' => URI_DATA . '/' . $this->cohort . '/' .
          $this->username . '/' . $this->latestVersion .'/');
        if ($includePassword) {
            $k['password'] = $this->getPassword();
        }

        $arr = \array_merge($k, $this->getArrayCopy());
        $keys = \array_map(function ($k) {
            return '<' . $k . '>';
        }, \array_keys($arr));

        return \str_replace($keys, \array_values($arr), $input);
    }
}
