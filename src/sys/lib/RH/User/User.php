<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\User;

/**
 * A user within the system.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class User extends \RH\AbstractModel {

	/**
	 * Scan text for keywords that can be replaced.
	 * 
	 * @param string $input Input to be scanned
	 * @return string Output with the substitutions made
	 */
	public function makeSubsts ($input) {
		$k = array ('password' => $this->getPassword(),
		            'imgDir' => URI_DATA . '/' . $this->cohort . '/' . $this->username . '/' . $this->latestVersion .'/');

		$arr = \array_merge ($k, $this->getArrayCopy ());
		$keys = \array_map (function ($k) {
			return '<' . $k . '>';
		}, \array_keys ($arr));

		return \str_replace ($keys, \array_values ($arr), $input);
	}

	/**
	 * Get the user's password
	 * 
	 * @return string Password of the user
	 */
	public function getPassword () {
		return \sha1 (SALT . $this->username);
	}

	/**
	 * List of possible substitutions.
	 * 
	 * @return string[] List of possible substitutions
	 */
	public static function substsKeys () {
		$k = array ('<password>', '<wordCount>', '<fundingStatment>', '<imgDir>');

		$oFileReader = \I::rh_file_reader ();
		$header = $oFileReader->readHeader (DIR_USR . Controller::USER_FILE);
		$ret = \array_merge ($k, array_map (function ($col) {
			return '<' . $col->name .'>';
		}, $header->toArray ()));

		\sort ($ret, SORT_STRING|SORT_NATURAL);
		return $ret;
	}

}