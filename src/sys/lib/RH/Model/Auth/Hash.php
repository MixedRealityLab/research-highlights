<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2016 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model\Auth;

/**
 * Model for login handling with passwords generated with as a hash. Uses the constant 'SALT' and the username to 
 * create a unique password for each user.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Hash extends AbstractAuth implements \RH\Singleton
{

    /**
     * Test user credentials.
     *
     * @param string $username Username to test with.
     * @param string $password Password to use to test with.
     * @return boolean
     */
    public function test($username, $password)
    {
        return $this->get('password', [$username]) === $password;
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
            'password' => true,
            'firstName' => false,
            'surname' => false,
            'email' => false
        ];

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
        switch ($key) {
            case 'password':
                return \sha1(SALT . $data[0]);

            default:
                return null;
        }
    }


}