<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Fetch all submissions, or a single submission for reading

\header('Content-type: application/json');

try {
    $cSubmission = I::RH_Submission();
    $mInput = I::RH_Model_Input();
    $cUser = I::RH_User();

    // Get the users for which we want to return their submission
    if (isset($mInput->user)) {
        $mUsers = array ($cUser->get($mInput->user));

    } elseif (isset($mInput->cohort)) {
        $cohort = $mInput->cohort;
        $mUsers = $cUser->getAll(null, function ($mUser) use ($cohort) {
            return $mUser->countSubmission && $mUser->cohort === $cohort;
        });

    } elseif (isset($mInput->keywords)) {
        $keywords = \preg_split('/,/', \trim($mInput->keywords), null, PREG_SPLIT_NO_EMPTY);
        $mKeywords = \RH\Keywords::get();
        $mUsers = new \RH\Model\Users();

        foreach ($keywords as $keyword) {
            if (isset($mKeywords[$keyword])) {
                $mUsers->merge($mKeywords->$keyword);
            }
        }

    } else {
        $mUsers = $cUser->getAll(null, function ($mUser) {
            return $mUser->countSubmission;
        });
    }

    // Format the submission for output
    $output = array ();
    foreach ($mUsers as $mUser) {
        try {
            $mSubmission = $cSubmission->get($mUser, false);

            $mSubmission->text = $mUser->makeSubsts($mSubmission->text);
        
            $textMd = $mSubmission->text;
            $textHtml = !empty($textMd) ? \RH\Submission::markdownToHtml($textMd) : '<em>No text submitted.</em>';

            $refMd = \trim($mSubmission->references);
            $refHtml = !empty($textMd) && !empty($refMd) ?  '<h1>References</h1>' . \RH\Submission::markdownToHtml($refMd) : '';
            
            $pubMd = \trim($mSubmission->publications);
            $pubHtml = !empty($pubMd) ? '<h1>Publications in the Last Year</h1>' . \RH\Submission::markdownToHtml($pubMd) : '';

            $mSubmission->html = $textHtml . $refHtml . $pubHtml;

            $output[] = \array_merge($mSubmission->toArray(), $mUser->toArray());
        } catch (\RH\Error\NoSubmission $e) {
        }
    }

    print \json_encode($output);
    exit;
} catch (\RH\Error $e) {
    print $e->toJson();
}
