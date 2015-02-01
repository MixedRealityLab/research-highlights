<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception relating to a core system error that is not the fault of the 
 * user. These errors should definitely be logged.
 * 
 * FIXME: add logging
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class SystemError extends \RH\Error {
	
	/**
	 * Throw the exception, detailing the cause. This message may be shown to
	 * the user.
	 * 
	 * @param string $message Detail of what triggered the `Exception`
	 */
	public function __construct ($message) {
		parent::__construct ('A system error occurred: ' . $message);
	}
	
}