<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\File;

/**
 * Columns within data files.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Column {

	/** @var string Name of the column */
	public $name;

	/** @var ColumnType Type of the column */
	public $type;

	/**
	 * Construct the column representation.
	 * 
	 * @param string $name Name of the column 
	 * @param ColumnType $type Type of the column 
	 */
	public function __construct ($name, $type) {
		$this->name = $name;
		$this->type = $type;
	}

}