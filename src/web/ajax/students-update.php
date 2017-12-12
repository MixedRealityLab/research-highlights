<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Update the wordcounts for each cohort.

use \RH\Model\InputValidator as V;

\header('Content-type: application/json');

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $mUsers = new \RH\Model\Users();

    $mUser = $cUser->login($mInput->username, $mInput->password, true);

    $studentsdata = explode("\r\n", $mInput->students);
    if ($studentsdata[0][0] === '#') {
        $titlesdata = array_shift($studentsdata);
        $titles = explode(',', substr($titlesdata, 1));
    } else {
        throw new \RH\Error\UserError('Do not remove the first line of the data!');
    }

    $expectedTitles = ['Cohort','Username','FirstName','Surname','Email','FundingStatement','LoginEnabled','ShowSubmission','Notify'];
    foreach ($expectedTitles as $expectedTitle) {
        if (!in_array($expectedTitle, $titles)) {
            throw new \RH\Error\UserError('Missing "'. $expectedTitle .'" column from user data!');
        }
    }

    $i = 0;
    $validatationType = [
        $expectedTitles[$i++] => V::NON_EMPTY|V::T_INT,
        $expectedTitles[$i++] => V::NON_EMPTY,
        $expectedTitles[$i++] => V::NON_EMPTY,
        $expectedTitles[$i++] => V::NON_EMPTY,
        $expectedTitles[$i++] => V::NON_EMPTY|V::T_STR_EMAIL,
        $expectedTitles[$i++] => V::NON_EMPTY,
        $expectedTitles[$i++] => V::NON_EMPTY|V::T_BOOL_STR,
        $expectedTitles[$i++] => V::NON_EMPTY|V::T_BOOL_STR,
        $expectedTitles[$i++] => V::NON_EMPTY|V::T_BOOL_STR
    ];

    $i = 0;
    $userParams = [
        $expectedTitles[$i++] => 'cohort',
        $expectedTitles[$i++] => 'username',
        $expectedTitles[$i++] => 'firstName',
        $expectedTitles[$i++] => 'surname',
        $expectedTitles[$i++] => 'email',
        $expectedTitles[$i++] => 'fundingStatementId',
        $expectedTitles[$i++] => 'enabled',
        $expectedTitles[$i++] => 'countSubmission',
        $expectedTitles[$i++] => 'emailOnChange'
    ];

    foreach ($studentsdata as $row => $strdata) {
        if (strlen(trim($strdata)) === 0) {
            continue;
        }

        $data = explode(',', $strdata);
        if (count($titles) !== count($data)) {
            throw new \RH\Error\UserError('Invalid number of columns on row '.$row .' ("'. $strdata .'")!');
        }
        $student = array_combine($titles, array_map('trim', $data));

        $mUser = new \RH\Model\User();
        $oValidator = $mInput->getValidator($mUser);

        foreach($student as $attr => $value) {
            if ($oValidator->testValue('Invalid value given on row '. $row .' ("'. $strdata .'") for attribute "'. $attr .'",', $value, $validatationType[$attr])) {
                $mUser->$userParams[$attr] = $oValidator->reformatValue($validatationType[$attr], $value);
            }
        }
        $mUsers->__set($mUser->username, $mUser);
    }

    print \json_encode(array ('success' => $cUser->updateUsers($mUsers, false) ? 1 : 0));
} catch (\RH\Error $e) {
    print $e->toJson();
}
