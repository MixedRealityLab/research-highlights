<?php

namespace CDT\File;

class Column {

	public $name;
	public $type;

	public function __construct ($name, $type) {
		$this->name = $name;
		$this->type = $type;
	}

}