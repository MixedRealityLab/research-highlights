<?php

/**
* Research Highlights engine
*
* Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
* See LICENCE for legal information.
*/

// Send an email to users

\header('Content-type: application/json');

try {
    $cSubmission = I::RH_Submission();
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();
    $oEmail = I::RH_Email();

    $username = \trim($mInput->username);
    $email = \trim($mInput->email);

    if (!empty($username)) {
        $mUser = $cUser->get($username);
    } elseif (!empty($email)) {
        $mUser = $cUser->getByEmail($email);
    } else {
        throw new \RH\Error\InvalidInput('Must provide username or email address.');
    }

    $mUserAdmin = $cUser->get(MAIL_ADMIN);

    if (is_null($mUser)) {
        throw new \RH\Error\InvalidInput('Could not find a user with those details.');
    }

    $from = '"'. $mUserAdmin->firstName . ' ' . $mUserAdmin->surname .'" <'. $mUserAdmin->email .'>';
    $replyTo = '"'. SITE_NAME .'" <'. EMAIL .'>';
    $oEmail->setHeaders($from, $replyTo);

    $subject = MAIL_FORGOT_PASS_SUBJ;
    $message = \nl2br(MAIL_FORGOT_PASS_MESG);

    if ($oEmail->send($mUser->username, $subject, \strip_tags($message), $message)) {
        print \json_encode(array ('success' => 1));
    } else {
        print \json_encode(array ('error' => 'The emails were not sent.'));
    }
} catch (\RH\Error $e) {
    print $e->toJson();
}
