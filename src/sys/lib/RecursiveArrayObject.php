<?php

/**
 * Recursive array object for convenient data storage.
 * 
 * @author: etconsilium@github
 * @license: BSDLv2
 */

/**
 * Data storage model.
 * 
 * @author: etconsilium@github; Martin Porcheron <martin@porcheron.uk>
 */
class RecursiveArrayObject extends \ArrayObject {
	
	/**
	 * Construct the data object, with initial data values, if any.
	 * 
	 * @param mixed[] $data Data to construct initial object with
	 * @param int $flags See {@link \ArrayObject}
	 * @param string $iterator_class Class to use as iterator
	 * @return New RecursiveArrayObject
	 */
	public function __construct ($data = null, $flags = self::ARRAY_AS_PROPS, $iterator_class = "ArrayIterator"){
		foreach ($data as $k => $v) {
			$this->__set ($k, $v);
		}

		return $this;
	}

	/**
	 * Set the value of an property.
	 * 
	 * @param string $key Name of the property of data to set
	 * @param mixed $value Value of the property.
	 * @return void
	 */
	public function __set ($key, $value){
		if (\is_array ($value) || \is_object ($value)) {
			$this->offsetSet ($key, (new self ($value)));
		} else {
			$this->offsetSet ($key, $value);
		}
	}


	/**
	 * Retrieve the value of a property.
	 * 
	 * @param string $key Name of the property to retrieve
	 * @throws \InvalidArgumentException if the property not found
	 */
	public function __get ($key){
		if ($this->offsetExists ($key)) {
			return $this->offsetGet ($key);
		} else if (\array_key_exists ($key, $this)) {
			return $this[$key];
		}

		throw new \InvalidArgumentException (\sprintf ('$this have not prop `%s`', $key));
	}


	/**
	 * @param string $key Property to search for in the dataset.
	 * @return bool `true` if an property exists
	 */
	public function __isset ($key){
		return \array_key_exists ($key, $this);
	}

	/**
	 * @param string $key Property to unset.
	 * @return void
	 */
	public function __unset ($key) {
		unset ($this[$key]);
	}
}