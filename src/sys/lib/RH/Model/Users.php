<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * A list of users.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Users extends AbstractModel {

	/**
	 * Create a new user within this list.
	 * 
	 * @param mixed $value Value of the user data.
	 * @return \RH\Model\User New User object.
	 */
	protected function newChild ($value) {
		return new User ($value);
	}

}