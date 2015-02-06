<?php

/**
* Research Highlights engine
*
* Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
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

	/** @var string Submission model cache */
	const DEFAULT_DATA_CACHE = 'defaultData.cache';

	/** @var string Submission model cache */
	const SUBMISSION_CACHE = 'submission-%s-%s.cache';

	/** @var \RH\Model\Submission Submission template */
	private $mDefaultData;

	/** @var \RH\Model\Submissions Cache submissions */
	private $mSubmissions;

	/**
	* @return \RH\Model\Submission Default submission template
	*/
	public function getDefaultData () {
		if (\is_null ($this->mDefaultData)) {
			$mDefaultData = new \RH\Model\Submission ();
			$mDefaultData->setCache (CACHE_SUBMISSION, self::DEFAULT_DATA_CACHE);

			if ($mDefaultData->hasCache ()) {
				$mDefaultData->loadCache ();
			} else {
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
				$mDefaultData->merge ($data)->saveCache ();
			}

			$this->mDefaultData = $mDefaultData;
		}

		return $this->mDefaultData;
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
		if (\is_null ($this->mSubmissions)) {
			$this->mSubmissions = new \RH\Model\Submissions ();
		}

		$username = $mUser->username;

		if (!isSet ($this->mSubmissions->$username)) {
			$file = \sprintf (self::SUBMISSION_CACHE, $username, $mUser->latestVersion);

			$mSubmission = new \RH\Model\Submission ();
			$mSubmission->setCache (CACHE_SUBMISSION, $file);

			if ($mSubmission->hasCache ()) {
				$mSubmission->loadCache ();
			} else {
				$oFileReader = \I::RH_File_Reader ();

				if ($includeDefaults) {
					$mSubmission->merge ($this->getDefaultData ());
				}

				$sufLen = \strlen (self::DEF_FILE_SUF);
				$readFileFn = function ($fileName) use ($sufLen) {
					return \substr ($fileName, 0 - $sufLen) === self::DEF_FILE_SUF;
				};

				$fileNameFn = function ($fileName) use ($sufLen) {
					$end = \strlen ($fileName) - $sufLen;
					return substr ($fileName, 0, $end);
				};

				$data = array ();
				try {
					$dir = $mUser->latestSubmission;
					$oFileReader = \I::RH_File_Reader ();
					$data = $oFileReader->multiRead ($dir, $readFileFn, $fileNameFn);
				} catch (\RH\Error\NoField $e) {
					if (!$includeDefaults) {
						throw new \RH\Error\NoSubmission ();
					}
				}

				$mSubmission->merge ($data)->makeSubsts ($mUser)->saveCache ();
			}

			$this->mSubmissions->$username = $mSubmission;
		}

		return $this->mSubmissions->$username;
	}

	/**
	* Convert Markdown syntax to HTML
	*
	* @param string $markdown Markdown-formatted text
	* @return string HTML formatted text
	*/
	public static function markdownToHtml ($markdown) {
		$pattern = '/!\[(.+)\]\((.+)\)/USu';
		$i = 1;
		$markdown = \preg_replace_callback ($pattern, function ($matches) use (&$i) {
			return '<div class="img"><img src="' . $matches[2] . '" alt="' . $matches[1] . '"><p><strong>Figure ' . $i++ . ': ' . $matches[1] . '</strong></p></div>';
		}, $markdown);

		return \Michelf\Markdown::defaultTransform ($markdown);
	}

}
