<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception throw when there is no user to perform an action on,.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class NoUser extends UserError {
	
	/**
	 * Throw the exception.
	 */
	public function __construct () {
		parent::__construct ('No user could be found with that username/password combination.');
	}
	
}