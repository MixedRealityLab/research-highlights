<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * A single keyword.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class SearchKeyword extends AbstractModel {

	/**
	 * @param mixed[] $data Data to construct initial object with
	 * @return \RH\Model\SearchKeyword
	 */
	public function __construct ($data = array()) {
		$this->offsetSet ('users', new \RH\Model\Users ());
		$this->offsetSet ('submissions', new \RH\Model\Submissions ());
		$this->offsetSet ('importance', -1);

		return parent::__construct ($data);
	}

}