<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\Input;

/**
 * Class to handle all `REQUEST` data into the website.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Model extends \CDT\Singleton {

	/** @var int Flag for GET data */
	const GET = 1;

	/** @var int Flag for POST data */
	const POST = -1;
	
	/** @var \CDT\Input\Model\Get All received GET data */
	private $get;

	/** @var \CDT\Input\Model\Post All received POST data */
	private $post;

	/**
	 * Construct the `Input` handler by importing the superglobals, and then
	 * destroying them. This makes this class the definitive source of input.
	 */
	public function __construct() {
		$this->get = new \CDT\Input\Get ($_GET);
		$this->post = new \CDT\Input\Post ($_POST);
		
		unset ($_GET, $_POST);
	}

	/**
	 * Retrieve either a GET or POST input, with the GET input having 
	 * precedence. This class maps unnamed variables that are publicly accessed
	 * to the input.
	 * 
	 * @param string $key Name of the input to retrieve
	 * @return string|null Input value, or `null` if it doesn't exist
	 */
	public function __get ($key) {
		return $this->get ($key);
	}

	/**
	 * Retrieve either a GET or POST input, with the GET input having 
	 * precedence. Specify which input to retrieve using the `$type` parameter.
	 * 
	 * @param string $key Name of the input to retrieve
	 * @param int $type Type of input to retrieve - use `Input::GET` or 
	 * 	`Input::POST`, don't supply to search both sets of data
	 * @return string|null Input value, or `null` if it doesn't exist
	 */ 
	public function get ($key, $type = 0) {
		if ($type > -1 && isSet ($this->get->$key)) {
			return $this->get->$key;
		} else if ($type < 1 && isSet ($this->post->$key)) {
			return $this->post->$key;
		}

		return null;
	}

	/**
	 * Retrieve all supplied input.
	 * 
	 * @param int $type Type of input to retrieve - use `Input::GET` or 
	 * 	`Input::POST`, don't supply to get both sets of data
	 * @return \CDT\Input\Model\Get|\CDT\Input\Model\Post All supplied data 
	 */
	public function getAll ($type) {
		if ($type > -1) {
			return $this->get;
		} else if ($type < 1) {
			return $this->post;
		}

		return \CDT\Input\Data::mergeArrays ($this->post, $this->get);
	}
	
}