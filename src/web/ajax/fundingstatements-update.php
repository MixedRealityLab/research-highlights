<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the funding statements

use \RH\Validator as V;

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mFundingStatements = new \RH\Model\FundingStatements();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    foreach ($mInput->funding[0] as $key => $id) {
        $mFundingStatement = new \RH\Model\FundingStatement();
        $oValidator = $mInput->getValidator($mFundingStatement);

        $identKey = 'funding[0]['. $key .']';

        $data = [
            ['Funding Statement ID', 'funding[0]['. $key .']', 'fundingStatementId', true, V::NON_EMPTY, null],
            ['Funding Statement', 'funding[1]['. $key .']', 'fundingStatement', true, V::NON_EMPTY, null]
        ];
        
        if ($oValidator->testAndSetAll($data, true, $identKey)) {
            $mFundingStatements->__set($mFundingStatement->fundingStatementId, $mFundingStatement);
        }
    }

    print \json_encode(array ('success' => $cUser->updateFunding($mFundingStatements) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}
