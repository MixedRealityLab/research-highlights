<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

/**
 * Singleton manager for the Research Highlights submission system.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
final class RH {

	/** @var RH Instance of the `RH` class */
	private static $instance = null;
	
	/** @var Object[] All Singleton instances */
	private $objects = array();
	
	/**
	 * Disable public construction of the `RH` class.
	 */
	private function __construct() {}

	/**
	 * Disallow cloning of the `RH` class.
	 */
	private function __clone() {}

	/**
	 * Retrieve a singleton instance of a class, or create it if it does not
	 * exist.
	 * 
	 * Classes should be stored in the _lib_ directory, and accessed based on 
	 * their path. All classes should have the file extension _.class.php_
	 * 
	 * For example, the class `ExampleClass`, in the namespace `ExampleNS`
	 * should be stored in _lib/examplens/exampleclass.class.php_ and accessed 
	 * using `RH::i()->examplens_exampleclass`
	 * 
	 * Note that paths *must be lowercase*, and so must variable names.
	 * 
	 * @param string $className Class to retrieve instance of.
	 * @return Object Instance of the desired class.
	 */
	public function __get ($className) {
		$className = \str_replace ('_', '\\', $className);
		if (!\array_key_exists ($className, $this->objects)) {
			$this->objects[$className] = new $className;
		}

		return $this->objects[$className];
	}

	/**
	 * @return RH The Singleton instance of `RH`
	 */
	public static function i() {
		if (\is_null (static::$instance)) {
			static::$instance = new static;
		}
		return self::$instance;
	}

}