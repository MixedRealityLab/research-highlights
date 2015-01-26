<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\User;

/**
 * A list of users.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Users extends \RH\AbstractModel {

	/**
	 * Create a new user within this list.
	 * 
	 * @param mixed $value Value of the user data.
	 * @return \RH\User\User New User object.
	 */
	protected function newChild ($value) {
		return new User ($value);
	}

}