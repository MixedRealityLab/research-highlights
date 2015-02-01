<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH;

/**
 * Controller for the search system
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Search implements \RH\Singleton {

	/** @var string Data file for search keywords */
	const SEARCH_CACHE = 'searchKeywords.cache';

	/** @var string Data file for search results cache */
	const RESULTS_CACHE = 'searchResults-%s.cache';

	/** @var float Weighting for exact matches */
	const WEIGHT_MATCH = 1.5;

	/** @var float Weighting for all terms */
	const WEIGHT_ALL_TERMS = 2;

	/** @var \RH\Model\SearchKeywords keyword model */
	private $mSearchKeywords;

	/**
	 * Construct, or reload the search index from file.
	 */
	public function __construct () {

	}

	/**
	 * Search the database and return the results.
	 * 
	 * @param string $terms Sequence of space seperated keywords
	 * @return \RH\Model\SearchResults
	 */
	public function search ($terms) {
		$resultsCache = \sprintf (self::RESULTS_CACHE, \base64_encode ($terms));

		$mSearchResults = new \RH\Model\SearchResults();
		$mSearchResults->setCache (CACHE_SEARCH, $resultsCache);

		if ($mSearchResults->hasCache ()) {
			$mSearchResults->loadCache ();
		} else {
			$mSearchKeywords = new \RH\Model\SearchKeywords();
			$mSearchKeywords->setCache (CACHE_SEARCH, self::SEARCH_CACHE);

			if ($mSearchKeywords->hasCache ()) {
				$mSearchKeywords->loadCache ();
			} else {
				$cUser = \I::RH_User ();
				$cSubmission = \I::RH_Submission ();

				$mUsers = $cUser->getAll (null, function ($user) {
					return $user->countSubmission;
				});

				foreach ($mUsers as $mUser) {
					try {
						$mSubmission = $cSubmission->get ($mUser, false);
						$mSearchKeywords->add ($mUser, $mSubmission);
					}
					catch (\RH\Error $e) {
					}
				}	

				$mSearchKeywords->saveCache ();	
			}
			
			$terms = \strtolower ($terms);
			$dbKeywords = \array_keys ($mSearchKeywords->getArrayCopy());
			$mRelevantSearchKeywords = array();

			$terms = \preg_replace ('/[^a-z0-9 *]+/i', '', $terms);
			$terms = \str_replace ('*', '.*', $terms); 
			$terms = \preg_split ('/\s+/', $terms, null, PREG_SPLIT_NO_EMPTY);
			foreach ($terms as $term) {
				if (\strlen ($term) < 3) {
					continue;
				}

				$matches = \preg_grep ('/^' . $term .'$/', $dbKeywords);
				foreach ($matches as $row => $foundTerm) {
					$mRelevantSearchKeywords[$foundTerm] = $mSearchKeywords[$foundTerm];
				}
			}

			$cUser = \I::RH_User();
			$cSubmission = \I::RH_Submission();
			foreach ($mRelevantSearchKeywords as $keyword => $mSearchKeyword) {
				foreach ($mSearchKeyword->getUsers () as $username) {
					if (!isSet ($mSearchResults->$username)) {

						$mUser = $cUser->get ($username);
						$mSubmission = $cSubmission->get ($mUser);

						$mSearchResults->$username = $mSubmission;
						$mSearchResults->$username->merge ($mUser);

						$html = \RH\Submission::markdownTohtml ($mSubmission->text);

						$mSearchResults->$username->html = $html;
						$mSearchResults->$username->found = 0;
					}


					$mSearchResults->$username = $mSubmission;

					$imp = $mSearchKeyword->importance;
					if (\in_array ($keyword, $terms)) {
						$mSearchResults->$username->found++;
						$imp *= self::WEIGHT_MATCH;
					}
					
					$mSearchResults->$username->weight += $imp;

					if ($mSearchResults->$username->found == \count ($terms)) {
						$mSearchResults->$username->weight *= self::WEIGHT_ALL_TERMS;
					}

				}
			}

			$mSearchResults->uasort(function(\RH\Model\SearchResult $a, \RH\Model\SearchResult $b) {
				return $b->weight - $a->weight;
			});

			$mSearchResults->saveCache ();
		}

		return $mSearchResults;
	}

}