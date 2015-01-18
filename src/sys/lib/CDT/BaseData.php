<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

/**
 * Data storage model.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
abstract class BaseData {

	/** @var string[] Data store */
	private $data = array();

	/**
	 * Construct the data object, with the initial data values.
	 */
	public function __construct ($data = array()) {
		$this->data = $data;
	}

	/**
	 * @return bool `true` if an item exists
	 */
	public function __isset ($key) {
		return \array_key_exists ($key, $this->data);
	}

	/**
	 * Retrieve the value of a item.
	 * 
	 * @param string $key Name of the item to retrieve
	 * @return string|null Value of the item
	 */
	public function __get ($key) {
		if (!\array_key_exists ($key, $this->data)) {
			return null;
		}

		return $this->data[$key];
	}

	/**
	 * Set the value of an item.
	 * 
	 * @param string $key Name of the item of data to set
	 * @param mixed $value Value of the item.
	 * @return void
	 */
	public function __set ($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 * @return mixed[] Data stored in an array.
	 */
	public function toArray() {
		return $this->data;
	}

	/**
	 * Convert an array of `BaseData` objects to a 2D array.
	 * 
	 * @param BaseData[] Objects to convert to arrays
	 * @return mixed[][] 2D array of data
	 */
	public static function toArrays ($data) {
		$result = array();
		foreach ($data as $k => $v) {
			$result[$k] = $v->toArray ();
		}
		return $result;
	}

	/**
	 * Convert an array of `BaseData` objects to a JSON string.
	 * 
	 * @param BaseData[] Objects to convert to arrays
	 * @return string JSON string
	 */
	public static function toJson ($data) {
		return \json_encode (self::toArrays ($data));
	}

	/**
	 * Convert multiple `BaseData` objects to a merged arrays.
	 * 
	 * @return BaseData[] Combined arrays
	 */
	public static function mergeArrays () {
		$args = \func_get_args ();
		$arrArgs = array();
		foreach ($args as $arg) {
			if ($arg instanceof \CDT\BaseData) {
				$arrArgs = \array_merge ($arrArgs, $arg->toArray());
			} else if (\is_array ($arg)) {
				$arrArgs = \array_merge ($arrArgs, $arg);
			}
		}

		return $arrArgs;
	}

	/**
	 * Convert multiple `BaseData` objects to a merged JSON string.
	 * 
	 * @return string JSON string
	 */
	public static function mergeJson () {
		$args = \func_get_args ();
		$arrArgs = array();
		foreach ($args as $arg) {
			if ($arg instanceof \CDT\BaseData) {
				$arrArgs = \array_merge ($arrArgs, $arg->toArray());
			} else if (\is_array ($arg)) {
				$arrArgs = \array_merge ($arrArgs, $arg);
			}
		}

		return \json_encode ($arrArgs);
	}

	/**
	 * Convert a 2D array to an array of `BaseData` objects.
	 * 
	 * @param mixed[][] Arrays to convert to array of BaseDatas
	 * @return BaseData[] Array of data
	 */
	public static function fromArrays ($data) {
		$res = array();
		$class = static::_get_class_name ();
		foreach ($data as $k => $v) {
			$res[$k] = new $class ($v);
		}
		return $res;
	}

	/** @return class Current class name */
	protected static function _get_class_name () {
		return get_called_class ();
	}

}