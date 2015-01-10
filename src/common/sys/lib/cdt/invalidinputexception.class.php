<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

class InvalidInputException extends \Exception {
	
	public function __construct ($message) {
	 	parent::__construct ('Invalid Input: ' . $message);
	}

}