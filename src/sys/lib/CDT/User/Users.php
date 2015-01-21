<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\User;

/**
 * A list of users.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Users extends \CDT\BaseData {

	/**
	 * Create a new user within this list.
	 * 
	 * @param mixed $value Value of the user data.
	 * @return \CDT\User\User New User object.
	 */
	protected function newChild ($value) {
		return new \CDT\User\User ($value);
	}

}