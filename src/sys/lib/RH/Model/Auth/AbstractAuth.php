<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2016 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model\Auth;

/**
 * Abstract authentication model link.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
abstract class AbstractAuth implements \RH\Singleton
{

    /**
     * Test user credentials.
     *
     * @param string $username Username to test with.
     * @param string $password Password to use to test with.
     * @return boolean
     */
    abstract public function test($username, $password);

    /**
     * Determine which if a value can be retrieved from the authentication model.
     *
     * @param string $key Column to determine if can be retrieved from the authentication model.
     * @return boolean
     */
    public function provides($key)
    {
        return false;
    }

    /**
     * Retrieve a value from from the authentication model.
     *
     * @param string $key Column to retrieve from the authentication model.
     * @param array $data Data necessary for retrieval.
     * @return string Value for the column, or `null`
     */
    abstract public function get($key, array $data);


}