<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * Class to handle all `REQUEST` data into the website.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Input extends AbstractModel implements \RH\Singleton {

	/**
	 * Construct the `Input` handler by importing the superglobals, and then
	 * destroying them. This makes this class the definitive source of input.
	 */
	public function __construct() {
		if (!isSet ($_GET)) {
			$_GET = array();
		}
		if (!isSet ($_POST)) {
			$_POST = array();
		}

		parent::__construct (\array_merge ($_GET, $_POST));
		unset ($_GET, $_POST);
	}
	
}