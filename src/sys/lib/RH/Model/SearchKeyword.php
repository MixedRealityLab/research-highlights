<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * A single keyword.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class SearchKeyword extends AbstractModel
{
    
    /** @var bool Disable recursive object creation */
    protected $recurse = false;

    /**
     * @param mixed[] $data Data to construct initial object with
     * @return \RH\Model\SearchKeyword
     */
    public function __construct($data = array ())
    {
        $this->offsetSet('users', array ());
        $this->offsetSet('importance', -1);

        return parent::__construct($data);
    }

    /**
     * Add a user to the list of users who have used this keyword.
     *
     * @param string $username Username of the user
     */
    public function addUser($username)
    {
        $this['users'][] = $username;
    }

    /**
     * @return string[] Usernames of users who have this keyword
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return int the number of users who use this keyword.
     */
    public function countUsers()
    {
        return \count($this['users']);
    }
}
