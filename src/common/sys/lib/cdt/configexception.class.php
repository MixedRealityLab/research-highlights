<?php

namespace CDT;

class ConfigException extends \CDT\SystemException {
	
	public function __construct ($message) {
		parent::__construct ('A system configuration error occurred: ' . $message);
	}
	
}