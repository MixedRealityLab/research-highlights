<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * A list of submissions.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Submissions extends AbstractModel {

	/**
	 * Create a new Submission within this list.
	 * 
	 * @param mixed $value Value of the Submission data.
	 * @return \RH\Model\Submission New Submission object.
	 */
	protected function newChild ($value) {
		return new Submission ($value);
	}

}