<?php

namespace CDT;

class SystemException extends \Exception {
	
	public function __construct ($message) {
		parent::__construct ('A system error occurred: ' . $message);
	}
	
}