<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * Data storage model.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
abstract class AbstractModel extends \RecursiveArrayObject {

	/**
	 * Construct the data object, with initial data values, if any.
	 * 
	 * @param mixed[] $data Data to construct initial object with
	 * @return \RH\Model\AbstractModel
	 */
	public function __construct ($data = array()) {
		return parent::__construct ($data);
	}

	/**
	 * Retrieve the value of a property.
	 * 
	 * @param string $key Name of the property to retrieve
	 * @throws \RH\Error\NoField if the property not found
	 */
	public function __get ($key){
		try {
			return parent::__get ($key);
		} catch (\InvalidArgumentException $e) {
			throw new \RH\Error\NoField ($key, get_called_class ());
		}
	}

	/**
	 * Fill this model with data from a string, with a numerical offset.
	 * 
	 * @param string $str String to extract data from 
	 * @param string $sep How to separate the data in the field.
	 * @throws \RH\Error\NoField if the property not found
	 * @return \RH\Model\AbstractModel
	 */
	public function fromString ($str, $sep = ',') {
		$data = @\explode ($sep, $str);
		foreach ($data as $k => $v) {
			$v = \trim ($v);
			if (!empty ($v)) {
				$this->$k = $v;
			}
		}
	}

	/**
	 * Convert an array of `AbstractModel` objects to a 2D array.
	 * 
	 * @param \RH\Model\AbstractModel[] Objects to convert to arrays
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
	 * Convert multiple `AbstractModel` objects to a merged arrays.
	 * 
	 * @return \RH\Model\AbstractModel[] Combined arrays
	 */
	public static function mergeArrays () {
		$args = \func_get_args ();
		$arrArgs = array();
		foreach ($args as $arg) {
			if ($arg instanceof AbstractModel) {
				$arrArgs = \array_merge ($arrArgs, $arg->toArray());
			} else if (\is_array ($arg)) {
				$arrArgs = \array_merge ($arrArgs, $arg);
			}
		}

		return $arrArgs;
	}

	/**
	 * Convert multiple `AbstractModel` objects to a merged JSON string.
	 * 
	 * @return string JSON string
	 */
	public static function mergeJson () {
		$args = \func_get_args ();
		$arrArgs = array();
		foreach ($args as $arg) {
			if ($arg instanceof AbstractModel) {
				$arrArgs = \array_merge ($arrArgs, $arg->toArray());
			} else if (\is_array ($arg)) {
				$arrArgs = \array_merge ($arrArgs, $arg);
			}
		}

		return \json_encode ($arrArgs);
	}

	/**
	 * Convert a 2D array to an array of `AbstractModel` objects.
	 * 
	 * @param mixed[][] Arrays to convert to array of AbstractModels
	 * @return \RH\Model\AbstractModel[] Array of data
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
	 * Append a second AbstractModel object, adds values with new numerical 
	 * offsets.
	 * 
	 * @param \RH\Model\AbstractModel|mixed Another AbstractModel object or array to 
	 * 	append into this one
	 * @return \RH\Model\AbstractModel thisßobject
	 */
	public function append ($data) {
		if (is_array ($data) || $data instanceof AbstractModel) {
			foreach ($data as $k => $v) {
				$k = $this->count();
				$this->$k = $v;
			}
		} else {
			parent::append ($data);
		}

		return $this;
	}

	/**
	 * Merge a second AbstractModel object, overwriting any existing values;
	 * 
	 * @param \RH\Model\AbstractModel|mixed[] Another AbstractModel object or 
	 * 	array to merge into this one
	 * @return \RH\Model\AbstractModel this object
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
	 * @return \RH\Model\AbstractModel
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