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
abstract class BaseData extends \RecursiveArrayObject {

	/**
	 * Construct the data object, with initial data values, if any.
	 * 
	 * @param mixed[] $data Data to construct initial object with
	 * @return New BaseData
	 */
	public function __construct ($data = array()) {
		return parent::__construct ($data);
	}


	/**
	 * @return mixed[] Data stored in an array.
	 */
	public function toArray() {
		return $this->getArrayCopy ();
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