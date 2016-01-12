<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception throw when the user account does not have sufficient privileges.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class NotAuthorised extends UserError
{
    
    /**
     * Throw the exception.
     */
    public function __construct()
    {
        parent::__construct('You are not authorised to view this page.');
    }
}
