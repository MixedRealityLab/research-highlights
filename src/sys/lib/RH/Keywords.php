<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH;

/**
 * Controller for keyword indexing.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Keywords implements \RH\Singleton {

	/** @var string Keywords model cache */
	const KEYWORDS_CACHE = 'keywords.cache';

	/** @var \RH\Model\Keywords List of keywords */
	private static $mKeywords = null;

	/**
	 * Retrieve a list of keywords
	 * 
	 * @return \RH\Model\Keywords
	 */
	public static function get () {
		if (\is_null (self::$mKeywords)) {
			$mKeywords = new \RH\Model\Keywords();
			$mKeywords->setCache (CACHE_KEYWORDS, self::KEYWORDS_CACHE);

			if ($mKeywords->hasCache ()) {
				$mKeywords->loadCache ();
			} else {
				$cSubmission = \I::RH_Submission ();
				
				$mUsers = \I::RH_User ()->getAll (null, function ($mUser) {
					return $mUser->latestVersion && $mUser->countSubmission;
				});

				foreach ($mUsers as $mUser) {
					$mSubmission = $cSubmission->get ($mUser, false);
					foreach ($mSubmission->getKeywords () as $keyword) {
						if (!isSet ($mKeywords->$keyword)) {
							$mKeywords->$keyword = new \RH\Model\Users();
						}
						$mKeywords->$keyword->offsetSet ($mUser->username, $mUser);
					}
				}

				$mKeywords->ksort ();
				$mKeywords->saveCache ();
			}
			
			self::$mKeywords = $mKeywords;
		}

		return self::$mKeywords;
	}
}