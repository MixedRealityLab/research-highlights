<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

class ConfigException extends \CDT\SystemException {
	
	public function __construct ($message) {
		parent::__construct ('A system configuration error occurred: ' . $message);
	}
	
}