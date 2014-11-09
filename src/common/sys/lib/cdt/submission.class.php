<?php

namespace CDT;

class Submission {
	
	private static $data;
	private static $input;
	private static $template;
	private static $user;

	private static function get (&$var, $className) {
		if (is_null ($var)) {
			$var = new $className;
		}

		return $var;
	}

	public static function data () {
		return self::get (self::$data, 'CDT\Data');
	}

	public static function input () {
		return self::get (self::$input, 'CDT\Input');
	}

	public static function template () {
		return self::get (self::$template, 'CDT\Template');
	}

	public static function user () {
		return self::get (self::$user, 'CDT\User');
	}

}