<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Submission;

/**
 * Controller for submissions made by users.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Controller implements \RH\Singleton {

	/** @var string Data file name suffix */
	const DAT_FILE_SUF = '.txt';

	/** @var string Default file name prefix */
	const DEF_FILE_PRE = 'default-';

	/** @var string Default file name suffix */
	const DEF_FILE_SUF = '.txt';

	/** @var Submission Submission template */
	private $defaultData;

	/**
	 * @return Submission Default submission template
	 */
	public function getDefaultData () {
		if (\is_null ($this->defaultData)) {
			$oFileReader = \I::rh_file_reader ();

			$sufLen = \strlen (self::DEF_FILE_SUF);
			$readFileFn = function ($fileName) use ($sufLen) {
				return \strpos ($fileName, self::DEF_FILE_PRE) === 0 &&
				    \strlen ($fileName) - \strrpos ($fileName, self::DEF_FILE_SUF) === $sufLen;
			};

			$preLen = \strlen (self::DEF_FILE_PRE);
			$fileNameFn = function ($fileName) use ($preLen, $sufLen) {
				$end = \strlen ($fileName) - $preLen - $sufLen;
				return substr ($fileName, $preLen, $end);
			};

			$data = $oFileReader->multiRead (DIR_USR, $readFileFn, $fileNameFn);
			$this->defaultData = new Submission ($data);
		}

		return $this->defaultData;
	}

	/**
	 * Retrieve a user's submission
	 * 
	 * @param \RH\User\User $username User's submission to retrieve
	 * @param bool $includeDefaults Use the submission template if the user has
	 * 	not submitted
	 * @return Submission
	 * @throws \RH\Error\NoUser if there is no user to retrieve submission for
	 * @throws \RH\Error\NoSubmission if there is no submission
	 */
	public function get (\RH\User\User $oUser, $includeDefaults = true) {
		$oFileReader = \I::rh_file_reader ();

		if ($includeDefaults) {
			$oSubmission = $this->getDefaultData();
		} else {
			$oSubmission = new Submission ();
		}

		$sufLen = \strlen (self::DEF_FILE_SUF);
		$readFileFn = function ($fileName) use ($sufLen) {
			return \substr ($fileName, 0 - $sufLen) === self::DEF_FILE_SUF;
		};

		$fileNameFn = function ($fileName) use ($sufLen) {
			$end = \strlen ($fileName) - $sufLen;
			return substr ($fileName, 0, $end);
		};

		$data = array();
		try {
			$dir = $oUser->latestSubmission;
			$data = $oFileReader->multiRead ($dir, $readFileFn, $fileNameFn);
		} catch (\RH\Error\NoField $e) {
			if (!$includeDefaults) {
				throw new \RH\Error\NoSubmission();
			}
		}

		$oSubmission->merge ($data)->makeSubsts ($oUser);

		return $oSubmission;
	}

	/**
	 * Retrieve a list of keywords
	 * 
	 * @param string $username User's keywords, or all keywords if `null`
	 * @param mixed[] $ret Results of the keyword scan
	 * @param int $total Total number of keywords found
	 * @return Keywords|null
	 */
	public function getKeywords ($username = null, &$ret = array(), &$total = 0) {
		$oUserController = \I::rh_user_controller ();

		// get keywords
		if (is_null ($username)) {
			$oUsers = $oUserController->getAll (null, function ($user) {
				return $user->countSubmission;
			});

			foreach ($oUsers as $oUser) {
				if (isSet ($oUser->latestSubmission)) {
					$this->getKeywords ($oUser->username, $ret, $total);
				}
			}
		} else {
			$oUser = $oUserController->get ($username);

			if (!isSet ($oUser->latestSubmission)) {
				return null;
			}

			$fileName =  $oUser->latestSubmission .'/keywords.txt';
			$file = @\file_get_contents ($fileName);
			$keywords = \explode (',', $file);			

			// count keywords
			foreach ($keywords as $keyword) {
				$keyword = \trim ($keyword);

				if ($keyword == '') {
					continue;
				}

				if (!isSet ($ret[$keyword])) {
					$ret[$keyword]['name'] = $keyword;
					$ret[$keyword]['users'] = array();
					$ret[$keyword]['num'] = 1;

					$colour = \array_shift (\unpack ('L*', $keyword) );
					$ret[$keyword]['colour'] = dechex ($colour % 16777216);
				} else {
					$ret[$keyword]['num']++;
				}

				$ret[$keyword]['users'][] = $oUser->username;
				$total++;
			}
		}

		// normalise
		foreach ($ret as $key=>$row) {
			$ret[$key]['weight'] = $row['num'] / $total;
		}

		return new Keywords ($ret);
	}

	/**
	 * Convert Markdown syntax to HTML
	 * 
	 * @param string $markdown Markdown-formatted text
	 * @return string HTML formatted text
	 */
	public function markdownToHtml ($markdown) {
		return \Michelf\Markdown::defaultTransform ($markdown);
	}

}