<?php

namespace CDT;

class Url {

	public function get ($page = PAG_HOME) {
		return URI_HOME . SYS_HTAC ? '' : '/index.php/' . $page;
	}

}