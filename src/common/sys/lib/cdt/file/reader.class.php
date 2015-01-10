<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\File;

class Reader {

	public function read ($file, $key = null, $readRowFn = null, $calcValuesFn = null) {
		$result = array();

		$oFile = new \SplFileObject ($file);
		$fileHeader = new \CDT\File\Header ($oFile->fgets());

		while (!$oFile->eof () && ($row = $oFile->fgets()) !== false) {
			if ($row[0] == '#') {
				continue;
			}

			$temp = array();
			$cols = \explode (',', $row);
			if (\count ($cols) > 1 && (\is_null ($readRowFn) || $readRowFn ($cols))) {
				foreach ($cols as $i => $col) {
					$colHeader = $fileHeader->get ($i);
					if ($colHeader->type === \CDT\File\ColumnType::LONG_STRING) {
						if (!isSet ($temp[$colHeader->name])) {
							$temp[$colHeader->name] = '';
						}

						$temp[$colHeader->name] .= $fileHeader->toType ($i, $col);
					} else {
						$temp[$colHeader->name] = $fileHeader->toType ($i, $col);
					}
				}

				if (!\is_null ($calcValuesFn)) {
					$calcValuesFn ($temp, $cols);
				}

				if (\is_null ($key)) {
					$result[] = $temp;
				} else {
					$result[$temp[$key]] = $temp;
				}
			}
		}

		return $result;
	}

}