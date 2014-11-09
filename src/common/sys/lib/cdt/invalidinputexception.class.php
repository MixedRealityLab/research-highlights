<?php

namespace CDT;

class InvalidInputException extends \Exception {
	
	public function __construct ($message) {
	 	parent::__construct ('Invalid Input: ' . $message);
	}

}