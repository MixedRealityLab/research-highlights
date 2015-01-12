<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

/**
 * Singleton base class for the Research Highlights submission system.
 * 
 * Extend from this class to automatically receive the Singleton manager at
 * construction-time.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
abstract class Singleton {

	/** @var RH Singleton manager */
	protected $rh;

	/** Construct the Singleton instance */
	public function __construct() {
		$this->rh = \CDT\RH::i();
	}

}