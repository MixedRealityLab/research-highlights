<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * List of funding statements.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class FundingStatements extends AbstractModel
{

    /**
     * Create a funding statement.
     *
     * @param mixed $value Value of the funding statement.
     * @return \RH\Model\FundingStatement New funding statement object.
     */
    protected function newChild($value)
    {
        return new FundingStatement($value);
    }
}
