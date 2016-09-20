<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * List of word counts.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class WordCounts extends AbstractModel
{

    /**
     * Create a new word count within this list.
     *
     * @param mixed $value Value of the word count data.
     * @return \RH\Model\WordCount New WordCount object.
     */
    protected function newChild($value)
    {
        return new WordCount($value);
    }
}
