<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

class Url {

	public function get ($page = PAG_HOME) {
		return URI_HOME . SYS_HTAC ? '' : '/index.php/' . $page;
	}

}