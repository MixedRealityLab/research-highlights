<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\User;

/**
 * A user within the system.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class User extends \CDT\AbstractModel {

	/**
	 * Scan text for keywords that can be replaced.
	 * 
	 * @param string $input Input to be scanned
	 * @return string Output with the substitutions made
	 */
	public function makeSubsts ($input) {
		$arr = $this->getArrayCopy ();
		return \str_replace (\array_keys ($arr), 
		                     \array_values ($arr), $input);
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
		$k = array ('<password>',
		            '<wordCount>',
		            '<deadline>',
		            '<fundingStatement>',
		            '<imgDir>');

		$oFileReader = RH::i()->cdt_file_reader;
		$header = $oFileReader->readHeader (DIR_USR . self::USER_FILE);
		return \array_merge ($k, $header->toArray ());
	}

}