<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

require 'autoload.php';

foreach (\glob(DIR_CRN . '/*.php') as $file) {
    include $file;
}
