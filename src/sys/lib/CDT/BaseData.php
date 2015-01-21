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
	// public static function toJson ($data) {
	// 	return \json_encode (self::toArrays ($data));
	// }

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
		$class = static::className ();
		foreach ($data as $k => $v) {
			$res[$k] = new $class ($v);
		}
		return $res;
	}

	/**
	 * Merge a second BaseData object, overwriting any existing values;
	 * 
	 * @param Traversable|mixed[] Another BaseData object or array to merge into 
	 * 	this one
	 * @return This BaseData object.
	 */
	public function merge ($data) {
		foreach ($data as $k => $v) {
			$this[$k] = $v;
		}

		return $this;
	}

	/**
	 * Filter this dataset.
	 * 
	 * @param function $filterFn Filter function that takes one parameter (the 
	 * 	data property) and returns a boolean value.
	 * @return modified BaseData
	 */
	public function filter ($filterFn) {
		$unset = array(); $i = $this->count() - 1;

		foreach ($this as $key => $value) {
			if (!$filterFn ($value)) {
				$unset[] = $key;
			}
		}

		foreach ($unset as $index) {
			$this->offsetUnset ($index);
		}

		return $this;
	}

	/**
	 * @return mixed[] Data stored in an array.
	 */
	public function toArray() {
		return $this->getArrayCopy ();
	}

	/**
	 * Convert this object to a JSON object.
	 * 
	 * @return string JSON object representation of this object.
	 */
	public function toJson () {
		return \json_encode ($this);
	}

	/**
	 * Convert this object to a JSON array.
	 * 
	 * @return string JSON array representation of this object.
	 */
	public function toArrayJson () {
		return \json_encode (\array_values ($this->toArray ()));
	}

}