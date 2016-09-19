<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the wordcounts for each cohort.

use \RH\Validator as V;

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mUsers = new \RH\Model\Users();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    foreach ($mInput->admin[0] as $key => $cohort) {
        $mUser = new \RH\Model\User();
        $cValidator = new \RH\Validator($mInput, $mUser);

        $identKey = 'admin[1]['. $key .']';

        $data = [
            ['Cohort', 'admin[0]['. $key .']', 'cohort', true, V::NON_EMPTY|V::T_INT, null],
            ['Username', 'admin[1]['. $key .']', 'username', true, V::NON_EMPTY, null],
            ['First Name', 'admin[2]['. $key .']', 'firstName', true, V::NON_EMPTY, null],
            ['Surname', 'admin[3]['. $key .']', 'surname', true, V::NON_EMPTY, null],
            ['Email address', 'admin[4]['. $key .']', 'email', true, V::NON_EMPTY|V::T_STR_EMAIL, null],
            ['Funding Statement ID', 'admin[5]['. $key .']', 'fundingStatementId', true, V::NON_EMPTY, null],
            ['Login Enabled', 'admin[6]['. $key .']', 'enabled', true, V::NON_EMPTY|V::T_BOOL_STR, null],
            ['Show Submission', 'admin[7]['. $key .']', 'countSubmission', true, V::NON_EMPTY|V::T_BOOL_STR, null],
            ['Notify', 'admin[8]['. $key .']', 'emailOnChange', true, V::NON_EMPTY|V::T_BOOL_STR, null]
        ];
        
        if ($cValidator->testAndSetAll($data, true, $identKey)) {
            $mUser->admin = true;
            $mUsers->__set($mUser->username, $mUser);
        }
    }

    print \json_encode(array ('success' => $cUser->updateUsers($mUsers, true) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}
