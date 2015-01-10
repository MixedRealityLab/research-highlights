<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

class Input {

	const GET = 1;
	const POST = -1;
	
	private $get = array();
	private $post = array();

	public function __construct() {
		$this->get = $_GET;
		$this->post = $_POST;
		
		unset ($_GET, $_POST);
	}

	public function get ($key, $type = 0) {
		if ($type > -1 && isSet ($this->get[$key])) {
			return $this->get[$key];
		} else if ($type < 1 && isSet ($this->post[$key])) {
			return $this->post[$key];
		}

		return null;
	}

	public function getAll ($type) {
		if ($type > -1) {
			return $this->get;
		} else if ($type < 1) {
			return $this->post;
		}

		return \array_merge ($this->post, $this->get);
	}
	
}