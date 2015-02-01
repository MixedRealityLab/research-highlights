<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * A search result.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class SearchResult extends AbstractModel {

	/** @var bool Create field when they are retrieved */
	protected $createOnGet = true;

	/**
	 * @param mixed[] $data Data to construct initial object with
	 * @return \RH\Model\SearchResult
	 */
	public function __construct ($data = array()) {
		$this->offsetSet ('weight', 0);

		return parent::__construct ($data);
	}


}