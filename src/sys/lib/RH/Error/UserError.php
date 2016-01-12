<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Error;

/**
 * An exception relating to data in, or out of the system and may be caused by
 * the user.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class UserError extends \RH\Error
{
    
    /**
     * Throw the exception, detailing the cause. The user may seem this message.
     *
     * @param string $message Detail of what triggered the `Exception`
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
