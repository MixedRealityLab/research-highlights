<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the funding statements

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mFundingStatements = new \RH\Model\FundingStatements();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    foreach ($mInput->funding[0] as $key => $id) {
        $fundingStatement = $mInput->funding[1][$key];
        if (!empty($id) && !empty($fundingStatement)) {
            $mFundingStatements->__set($id, array ('fundingStatementId' => $id, 'fundingStatement' => $fundingStatement));
        }
    }

    print \json_encode(array ('success' => $cUser->updateFunding($mFundingStatements) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}
