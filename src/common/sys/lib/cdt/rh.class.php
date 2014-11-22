<?php

namespace CDT;

final class RH {

	private static $instance = null;
	
	private $objects = array();
	
	private function __construct() {}

	private function __clone() {}

	public function __get ($className) {
		$className = \str_replace ('_', '\\', $className);
		if (!\array_key_exists ($className, $this->objects)) {
			$this->objects[$className] = new $className;
		}

		return $this->objects[$className];
	}

	public static function i() {
        if (\is_null (static::$instance)) {
            static::$instance = new static;
        }
		return self::$instance;
	}


}