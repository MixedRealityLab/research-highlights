<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\Error;

/**
 * An exception relating to invalid user input.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class InvalidInput extends \Exception {
	
	/**
	 * Throw the exception, detailing the cause.
	 * 
	 * @param string $message Detail of what triggered the `Exception`
	 */
	public function __construct ($message) {
	 	parent::__construct ('Invalid Input: ' . $message);
	}

}