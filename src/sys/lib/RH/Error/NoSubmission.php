<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception throw when there is no submission for the given user.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class NoSubmission extends UserError {
	
	/**
	 * Throw the exception.
	 */
	public function __construct () {
		parent::__construct ('No submission could be found for the given user!');
	}
	
}