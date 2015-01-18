<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\File;

/**
 * Helper class for reading files.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Reader extends \CDT\Singleton {

	/**
	 * Read a condensed single-file CSV file header.
	 * 
	 * The header row indicates the name of the column, and its type, separated
	 * by a colon. There are four types: `bool`, `int`, `str` and `str_rem`. The
	 * first three are obvious, the fourth gobbles up everything (including 
	 * commas) that come after it, thus must be the last column.
	 * 
	 * Example header:
	 * <code>
	 * colA:str,colB:int,colC:bool,colD:str_rem
	 * </code>
	 * 
	 * @see \CDT\File\Reader\read()
	 * @param string $file Path to file to open
	 * @return \CDT\File\Header File header information.
	 */
	public function readHeader ($file) {
		$result = array();

		$handle = @\fopen ($file, 'r');
		if ($handle === false) {
			throw new \Exception ('Could not open ' . $file);
		}

		$fileHeader = new \CDT\File\Header (\fgets ($handle));

		@\fclose ($handle);

		return $fileHeader;
	}

	/**
	 * Read a condensed single-file CSV data stream. The file must have a header
	 * row. Rows can be excluded from being read by starting with a `#`.
	 * 
	 * The header row indicates the name of the column, and its type, separated
	 * by a colon. There are four types: `bool`, `int`, `str` and `str_rem`. The
	 * first three are obvious, the fourth gobbles up everything (including 
	 * commas) that come after it, thus must be the last column.
	 * 
	 * Example header:
	 * <code>
	 * colA:str,colB:int,colC:bool,colD:str_rem
	 * </code>
	 * 
	 * @param string $file Path to file to open
	 * @param string $key The column name that should be used as an index in the
	 * 	returned data array, if `null`, a numerical array is returned
	 * @param function|null $readRowFn A function that determines whether a row 
	 * 	should, or not; the function is provided an array of the columns in a
	 * 	row as a parameter and should return a `bool` value.
	 * @param function|null $calcValuesFn Function that generates additional 
	 * 	columns to include, on a per row-basis. This function takes two 
	 * 	parameters: an associate array of the row's data and all columns for 
	 * 	the current row. The associate array should be passed-by-reference and
	 * 	modified.
	 * @throws CDT\Error\Data if the file does not exist
	 * @return string[] All data read in from the file.
	 */
	public function read ($file, $key = null, $readRowFn = null, $calcValuesFn = null) {
		$result = array();

		$handle = @\fopen ($file, 'r');
		if ($handle === false) {
			throw new \Exception ('Could not open ' . $file);
		}

		$fileHeader = new \CDT\File\Header (\fgets ($handle));

		while (($row = \fgets ($handle)) !== false) {
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
							$temp[$colHeader->name] = $fileHeader->toType ($i, $col);
						} else {
							$temp[$colHeader->name] .= ', ' . $fileHeader->toType ($i, $col);
						}
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

		@\fclose ($handle);

		return $result;
	}


	/**
	 * Read multiple files that contain data, without headers.
	 * 
	 * 
	 * @param string $dir Directory that contains the files 
	 * @param function|null $readFileFn Function to determine if a file in the 
	 * 	given directory should be read. If not specified, all files are read. 
	 * 	This function takes a file name as its parameter and returned a `bool`.
	 * @param function|null $fileNameFn Function to determine the name given to 
	 * 	the data in the associate array returned. If not given, the file name is used.
	 * @return string[] associate array of data from the files.
	 */
	public function multiRead ($dir, $readFileFn = null, $fileNameFn = null) {
		$res = array();

		if (\is_null ($readFileFn)) {
			$readFileFn = function ($fileName) {
				return true;
			};
		}

		if (\is_null ($fileNameFn)) {
			$fileNameFn = function ($fileName) {
				return $fileName;
			};
		}

		if (\is_dir ($dir) && $dh = \opendir ($dir)) {
			while (($file = \readdir ($dh)) !== false) {
				if ($file != '.' && $file != '..' && $readFileFn ($file)) {
					$res[$fileNameFn ($file)] = @\file_get_contents ($dir .'/'. $file);
				}
			}
			\closedir ($dh);
		}

		return $res;
	}

}