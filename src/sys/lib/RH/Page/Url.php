<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Page;

/**
 * Class responsible for generating URLs within the Research Highlights system.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Url implements \RH\Singleton {

	/**
	 * Generate a URL for a given page.
	 * 
	 * @param string $page Page to generate a URL for, by default this will
	 * 	return the homepage value.
	 * @return string Fully-formed URL
	 */
	public function get ($page = PAG_HOME) {
		return URI_HOME . SYS_HTAC ? '' : '/index.php/' . $page;
	}

}