<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception throw when the user account is disabled.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class AccountDisabled extends UserError {
	
	/**
	 * Throw the exception.
	 */
	public function __construct () {
		parent::__construct ('This account has been disabled.');
	}
	
}