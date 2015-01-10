<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\File;

class Column {

	public $name;
	public $type;

	public function __construct ($name, $type) {
		$this->name = $name;
		$this->type = $type;
	}

}