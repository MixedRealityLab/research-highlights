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

    foreach ($mInput->student[0] as $key => $cohort) {
        $mUser = new \RH\Model\User();
        $cValidator = new \RH\Validator($mInput, $mUser);

        $identKey = 'student[1]['. $key .']';

        $data = [
            ['Cohort', 'student[0]['. $key .']', 'cohort', true, V::NON_EMPTY|V::T_INT, null],
            ['Username', 'student[1]['. $key .']', 'username', true, V::NON_EMPTY, null],
            ['First Name', 'student[2]['. $key .']', 'firstName', true, V::NON_EMPTY, null],
            ['Surname', 'student[3]['. $key .']', 'surname', true, V::NON_EMPTY, null],
            ['Email address', 'student[4]['. $key .']', 'email', true, V::NON_EMPTY|V::T_STR_EMAIL, null],
            ['Funding Statement ID', 'student[5]['. $key .']', 'fundingStatementId', true, V::NON_EMPTY, null],
            ['Login Enabled', 'student[6]['. $key .']', 'enabled', true, V::NON_EMPTY|V::T_BOOL_STR, null],
            ['Show Submission', 'student[7]['. $key .']', 'countSubmission', true, V::NON_EMPTY|V::T_BOOL_STR, null],
            ['Notify', 'student[8]['. $key .']', 'emailOnChange', true, V::NON_EMPTY|V::T_BOOL_STR, null]
        ];
        
        if ($cValidator->testAndSetAll($data, true, $identKey)) {
            $mUser->admin = false;
            $mUsers->__set($mUser->username, $mUser);
        }
    }

    print \json_encode(array ('success' => $cUser->updateUsers($mUsers, false) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}
