<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception thrown when an AbtractData field does not exist.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class NoField extends SystemError {
	
	/**
	 * Throw the exception.
	 * 
	 * @param string $name Name of the field.
	 * @param string $type Name of the data type.
	 */
	public function __construct ($name, $type) {
		parent::__construct ('No field `' . $name . '` in `' . $type . '`.');
	}
	
}