<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\File;

class Header {

	private $columns = array ();

	private $gobbleFrom = -1;

	public function __construct ($row) {
		$cols = \explode (',', $row);
		foreach ($cols as $i => $col) {
			$data = \explode (':', \trim ($col));
			if (\count ($data) !== 2) {
				throw new \CDT\ConfigException ('File has incorrect number of parameters per column (has ' . count($data) .') in ' . $col);
			}
			$this->add ($data[0], $data[1]);
		}
	}


	private function add ($column, $type) {
		$type = \CDT\File\ColumnType::fromString ($type);
		$this->columns[] = new \CDT\File\Column ($column, $type);

		if ($type == \CDT\File\ColumnType::LONG_STRING) {
			$this->gobbleFrom = \count ($this->columns) - 1;
		}
	}

	public function get ($id) {
		if ($this->gobbleFrom >= 0 && $this->gobbleFrom < $id) {
			return $this->columns[$this->gobbleFrom];
		}

		return count ($this->columns) < $id ? null : $this->columns[$id];
	}

	public function toType ($id, $str) {
		$col = $this->get ($id);

		if (\is_null ($col)) {
			return null;
		}

		return \CDT\File\ColumnType::strTo ($col->type, $str);
	}

}