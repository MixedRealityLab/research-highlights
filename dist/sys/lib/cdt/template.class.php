<?php

namespace CDT;

class Template {

	private $lastCache = '';

	private $data = array();

	public function __construct() {
		\ob_start ();
	}
	
	public function add ($key, $value) {
		if (isset ($this->data[$key]) && is_array ($this->data[$key])) {
			$this->data[$key][] = $value;
		} else if (isset ($this->data[$key])) {
			$this->data[$key] = array ($this->data[$key], $value);
		} else {
			$this->data[$key] = array ($value);
		}
	}

	public function set ($key, $value = 0) {
		if ($value || !isset ($this->data[$key])) {
			$this->data[$key] = $value;
		}
	}

	public function load ($template) {
		if (\is_file (DIR_TPL . '/_' . $template . '.php')) {
			include DIR_TPL . '/_' . $template . '.php';
		}

		if (\is_file (DIR_TPL . '/' . $template . '.php')) {
			\extract ($this->data, EXTR_SKIP);
			
			$this->startCapture ();
			include DIR_TPL . '/' . $template . '.php';
			return $this->endCapture ();
		}

		return null;
	}

	public function startCapture () {
		$this->lastCache = \ob_get_contents ();
		\ob_clean ();
	}

	public function endCapture () {
		$capture = \ob_get_contents ();
		\ob_clean ();
		print $this->lastCache;
		return $capture;
	}

}