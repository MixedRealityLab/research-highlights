<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * List of search results.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class SearchResults extends AbstractModel
{

    /** @var bool Create field when they are retrieved */
    protected $createOnGet = true;

    /**
     * Create a new search result within this list.
     *
     * @param mixed $value Value of the result data.
     * @return \RH\Model\SearchResult New result object.
     */
    protected function newChild($value)
    {
        return new SearchResult($value);
    }
}
