<?php

namespace CDT\File;

abstract class ColumnType {

	const BOOL = 0;
	const INTEGER = 1;
	const STRING = 2;
	const LONG_STRING = 3;

	public static function fromString ($str) {
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
				throw new \CDT\SystemException ('Could not find type of ' . $str);
		}
	}

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