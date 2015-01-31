<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH;

/**
 * Class responsible for assisting with templating.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Template {

	/** @var string Output buffering cache */
	private $lastCache = '';

	/** @var mixed[] Data to include in to the template */
	private $data = array();

	/**
	 * Begin output buffering, to be used in the templating.
	 */
	public function __construct() {
		\ob_start ();
	}
	
	/**
	 * Add a piece of data to be includable within template, as an array of
	 * items. If the item doesn't exist, only a single item exists, if the same
	 * key is used in a successive call to `add`, an array is created with both
	 * values.
	 * 
	 * @param string $key Name of a piece of data to include within a template.
	 * @param mixed $value Data value to be includeded. 
	 * @return void
	 */
	public function add ($key, $value) {
		if (isSet ($this->data[$key]) && is_array ($this->data[$key])) {
			$this->data[$key][] = $value;
		} else if (isSet ($this->data[$key])) {
			$this->data[$key] = array ($this->data[$key], $value);
		} else {
			$this->data[$key] = array ($value);
		}
	}

	/**
	 * Set a value.
	 * 
	 * @param string $key Name of a piece of data to include within a template.
	 * @param mixed $value Data value to be includeded. 
	 * @return void
	 */
	public function set ($key, $value = 0) {
		if ($value || !isSet ($this->data[$key])) {
			$this->data[$key] = $value;
		}
	}

	/**
	 * Load a template.
	 * 
	 * @param string $template Template file name.
	 * @return string|null The template output, `null`.
	 */
	public function load ($template) {
		if (\is_file (DIR_WTP . '/_' . $template . '.php')) {
			include DIR_WTP . '/_' . $template . '.php';
		}

		if (\is_file (DIR_WTP . '/' . $template . '.php')) {
			\extract ($this->data, EXTR_SKIP);
			
			$this->startCapture ();
			include DIR_WTP . '/' . $template . '.php';
			return $this->endCapture ();
		}

		return null;
	}

	/**
	 * Start capturing output to be stored in a variable.
	 * 
	 * @return void
	 */
	public function startCapture () {
		$this->lastCache = \ob_get_contents ();
		\ob_clean ();
	}

	/**
	 * End capture and return the output buffer to its previous state.
	 * 
	 * @return string Captured output.
	 */
	public function endCapture () {
		$capture = \ob_get_contents ();
		\ob_clean ();
		print $this->lastCache;
		return $capture;
	}

}