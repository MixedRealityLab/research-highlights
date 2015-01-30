<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\File;

/**
 * Column types for data files.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
abstract class ColumnType {

	/** @var int Flag for `bool` column types. */
	const BOOL = 0;

	/** @var int Flag for `int` column types. */
	const INTEGER = 1;

	/** @var int Flag for `str` column types. */
	const STRING = 2;

	/** @var int Flag for `str_rem` column types. */
	const LONG_STRING = 3;

	/**
	 * Convert a column type string into its correct flag.
	 * 
	 * @param string $str String representation of the column type.
	 * @return int Column type flag.
	 */
	public static function fromString ($str) {
		$str = \trim ($str);
		switch ($str) {
			case 'bool':
				return self::BOOL;
			case 'int':
				return self::INTEGER;
			case 'str':
				return self::STRING;
			case 'str_rem':
				return self::LONG_STRING;
			default:
				throw new \RH\Error\System ('Could not find type of ' . $str);
		}
	}


	/**
	 * Convert a value to its correct type, based on its column type.
	 * 
	 * @param int $type Flag of the column type.
	 * @param string $str String representation of the value
	 * @return mixed Correctly typed value
	 */
	public static function strTo ($type, $str) {
		$str = \trim ($str);
		switch ($type) {
			case self::BOOL:
				if ($str == '0') return false;
				else return true;
			case self::INTEGER:
				return \intval ($str);
			case self::STRING:
			case self::LONG_STRING:
				return $str;
		}
	}

}