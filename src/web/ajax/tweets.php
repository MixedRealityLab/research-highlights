<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all tweets

\header('Content-type: application/json');

$cSubmission = I::RH_Submission();
$cUser = I::RH_User();

try {
    $mUsers = $cUser->getAll(null, function ($mUser) {
        return $mUser->countSubmission;
    });

    $data = array();
    foreach ($mUsers as $mUser) {
    	try {
        $mSubmission = $cSubmission->get($mUser, false);

    	$data[] = array(
    		'username' => $mUser->username,
    		'author' => $mUser->firstName .' '. $mUser->surname,
    		'cohort' => $mUser->cohort,
    		'title' => $mSubmission->title,
    		'tweet' => $mSubmission->tweet);
        } catch (\RH\Error\NoSubmission $e) {
        } catch (\RH\Error\NoField $e) {
        }
    }

    \shuffle($data);
    
    print \json_encode($data);
} catch (\RH\Error $e) {
    print $e->toJson();
}