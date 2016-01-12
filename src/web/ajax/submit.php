<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Save a user's submission

\header('Content-type: application/json');

\define('NO_CACHE', true);

try {
    $cUser = I::RH_User();
    $mInput = I::RH_Model_Input();

    $mUser = $cUser->login($mInput->editor, $mInput->password);

    if ($mUser->admin && isset($mInput->username)) {
        $mUser = $cUser->get(\strtolower($mInput->username));
        $cUser->overrideLogin($mUser);
    }

    $cSubmission = I::RH_Submission();

    if (!isset($mInput->username)) {
        throw new \RH\Error\InvalidInput('Issue with login (missing username); please copy your work to a text document and try again');
    }

    // Go ahead and save the submission!
    if (!isset($mInput->cohort) && !isset($mInput->title)
        && !isset($mInput->keywords) && !isset($mInput->text)) {
        throw new \RH\Error\InvalidInput('Missing provide a cohort, title, keywords and your submission text.');
    }

    $cohortDir = DIR_DAT . '/' . $mInput->cohort;
    if ($mInput->cohort !== $mUser->cohort
        || !is_numeric($mInput->cohort) || !is_dir($cohortDir)) {
        throw new \RH\Error\InvalidInput('Invalid cohort supplied');
    }

    $mSubmission = new \RH\Model\Submission($mInput);

    $html = \RH\Submission::markdownToHtml($mSubmission->text);

    $images = array ();
    \preg_match_all('/(<img).*(src\s*=\s*("|\')([a-zA-Z0-9\.;:\/\?&=\-_|\r|\n]{1,})\3)/isxmU', $html, $images, PREG_PATTERN_ORDER);

    $id = 0;

    foreach ($images[4] as $url) {
        $path_parts = \pathinfo($url);
        $ext = $path_parts['extension'];
        if (\strpos($ext, '?') !== false) {
            $ext = \substr($ext, 0, \strpos($ext, '?'));
        }

        $filename = 'img-' . $id++ . '.' . $ext;

        $mSubmission->addImage($filename, $url);
        $mSubmission->text = \str_replace($url, '<imgDir>' . $filename, $mSubmission->text);
    }

    $mSubmission->keywords = \strtolower($mSubmission->keywords);

    $mSubmission->website = !\is_null($mSubmission->website) && $mSubmission->website != 'http://' ? \trim($mSubmission->website) : '';
    $mSubmission->twitter = \strlen($mSubmission->twitter) > 0 && $mSubmission->twitter[0] != '@' ? '@' . $mSubmission->twitter : $mSubmission->twitter;

    unset($mSubmission->files);

    $mSubmission->save();

    if (MAIL_ON_CHANGE_USRS !== null) {
        $oEmail = I::RH_Email();

        $from = '"'. $mUser->firstName . ' ' . $mUser->surname .'" <'. $mUser->email .'>';
        $oEmail->setHeaders($from, $from);

        $usernames = \preg_split('/,/', \trim(MAIL_ON_CHANGE_USRS), null, PREG_SPLIT_NO_EMPTY);
        $unamesMail = array ();
        foreach ($usernames as $username) {
            $tempU = $cUser->get($username);
            if ($tempU->emailOnChange) {
                $unamesMail[] = $username;
            }
        }

        $message = '<strong>Tasks</strong><br>';
        $message .= '&bull; <a href="' . URI_ROOT . '/#read=<username>" target="_blank">Read submission</a><br>';
        $message .= '&bull; <a href="' . URI_ROOT . '/login" target="_blank">Edit submission</a> (login and then enter the username <em><username></em> in the bottom left)';
        $message = $mUser->makeSubsts($message);
        $subject = $mUser->makeSubsts(MAIL_ON_CHANGE_SUBJ);

        $message .= '<br><br><strong>Account Details</strong><br>Username: <em><username></em><br>Password: <em><password></em>';

        $oEmail->sendAll($unamesMail, $subject, \strip_tags($message), $message) ? '1' : '-1';
    }

    print \json_encode(array ('success' => '1'));
} catch (\RH\Error $e) {
    print $e->toJson();
}
