<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception throw when there is no user to perform an action on,.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class NoUser extends UserError
{

    /**
     * Throw the exception.
     */
    public function __construct()
    {
        parent::__construct('No matching user could be found, sorry.');
    }
}
