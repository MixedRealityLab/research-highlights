<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * Base RH exception.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
abstract class Error extends \Exception {
	
	/**
	 * Return a JSON encoded error message.
	 * 
	 * Takes the error message and wraps it in a JSON string.
	 * 
	 * @param string $name JSON object parameter to store the error message.
	 * @return JSON encoded string.
	 */
	public function toJson ($name = "error") {
		return \json_encode (array ($name => $this->getMessage()));
	}
	
}