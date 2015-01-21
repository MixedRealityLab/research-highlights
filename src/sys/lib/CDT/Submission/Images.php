<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\Submission;

/**
 * A list of images.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Images extends \CDT\AbstractModel {

	/**
	 * Create a new image within this list.
	 * 
	 * @param mixed $value Value of the image data.
	 * @return Image New Image object.
	 */
	protected function newChild ($value) {
		return new Image ($value);
	}

}