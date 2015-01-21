<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\Page;

/**
 * Class to handle all `REQUEST` data into the website.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Input extends \CDT\AbstractModel {

	/**
	 * Construct the `Input` handler by importing the superglobals, and then
	 * destroying them. This makes this class the definitive source of input.
	 */
	public function __construct() {
		parent::__construct (\array_merge ($_GET, $_POST));		
		unset ($_GET, $_POST);
	}
	
}