<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH;

/**
 * Controller for submissions made by users.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Submission implements \RH\Singleton {

	/** @var string Data file name suffix */
	const DAT_FILE_SUF = '.txt';

	/** @var string Default file name prefix */
	const DEF_FILE_PRE = 'default-';

	/** @var string Default file name suffix */
	const DEF_FILE_SUF = '.txt';

	/** @var Submission Submission template */
	private $defaultData;

	/**
	 * @return \RH\Model\Submission Default submission template
	 */
	public function getDefaultData () {
		if (\is_null ($this->defaultData)) {
			$oFileReader = \I::RH_File_Reader ();

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
			$this->defaultData = new \RH\Model\Submission ($data);
		}

		return $this->defaultData;
	}

	/**
	 * Retrieve a user's submission
	 * 
	 * @param \RH\Model\User $mUser User's submission to retrieve
	 * @param bool $includeDefaults Use the submission template if the user has
	 * 	not submitted
	 * @return \RH\Model\Submission
	 * @throws \RH\Error\NoUser if there is no user to retrieve submission for
	 * @throws \RH\Error\NoSubmission if there is no submission
	 */
	public function get (\RH\Model\User $mUser, $includeDefaults = true) {
		$oFileReader = \I::RH_File_Reader ();

		if ($includeDefaults) {
			$mSubmission = $this->getDefaultData();
		} else {
			$mSubmission = new \RH\Model\Submission ();
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
			$dir = $mUser->latestSubmission;
			$data = $oFileReader->multiRead ($dir, $readFileFn, $fileNameFn);
		} catch (\RH\Error\NoField $e) {
			if (!$includeDefaults) {
				throw new \RH\Error\NoSubmission();
			}
		}

		return $mSubmission->merge ($data)->makeSubsts ($mUser);
	}

	/**
	 * Retrieve a list of keywords
	 * 
	 * @return \RH\Model\Keywords
	 */
	public function getKeywords () {
		$file = DIR_DAT . '/keywords.txt';
		\clearstatcache (true, $file);

		$mKeywords = new \RH\Model\Keywords ();

		if (\is_file ($file) && \filemtime ($file) + KEY_CACHE < \date ('U')) {
			$mUsers = I::RH_User ()->getAll (null, function ($mUser) {
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

			@\file_put_contents ($file, $mKeywords->serialize ());
			@\chmod ($file, 0777);
		} else {
			$str = @\file_get_contents ($file);
			$mKeywords->unserialize ($str);
		}

		return $mKeywords;
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