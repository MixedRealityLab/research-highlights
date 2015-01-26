<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception relating to configuration files.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Configuration extends SystemError {
	
	/**
	 * Throw the exception, detailing the cause.
	 * 
	 * @param string $message Detail of what triggered the `Exception`
	 */
	public function __construct ($message) {
		parent::__construct ('A configuration error occurred: ' . $message);
	}
	
}