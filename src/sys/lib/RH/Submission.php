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
	 * @param \RH\Model\User $U User's submission to retrieve
	 * @param bool $includeDefaults Use the submission template if the user has
	 * 	not submitted
	 * @return \RH\Model\Submission
	 * @throws \RH\Error\NoUser if there is no user to retrieve submission for
	 * @throws \RH\Error\NoSubmission if there is no submission
	 */
	public function get (\RH\Model\User $U, $includeDefaults = true) {
		$oFileReader = \I::RH_File_Reader ();

		if ($includeDefaults) {
			$S = $this->getDefaultData();
		} else {
			$S = new \RH\Model\Submission ();
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
			$dir = $U->latestSubmission;
			$data = $oFileReader->multiRead ($dir, $readFileFn, $fileNameFn);
		} catch (\RH\Error\NoField $e) {
			if (!$includeDefaults) {
				throw new \RH\Error\NoSubmission();
			}
		}

		return $S->merge ($data)->makeSubsts ($U);
	}

	/**
	 * Retrieve a list of keywords
	 * 
	 * @return \RH\Model\Keywords
	 */
	public function getKeywords () {
		$file = DIR_DAT . '/keywords.txt';
		\clearstatcache (true, $file);

		$Ks = new \RH\Model\Keywords ();

		if (\is_file ($file) && \filemtime ($file) + KEY_CACHE < \date ('U')) {
			$Us = I::RH_User ()->getAll (null, function ($U) {
				return $U->latestVersion && $U->countSubmission;
			});

			foreach ($Us as $U) {
				$S = $oSubmission->get ($U, false);
				foreach ($S->getKeywords () as $keyword) {
					if (!isSet ($Ks->$keyword)) {
						$Ks->$keyword = new \RH\Model\Users();
					}
					$Ks->$keyword->offsetSet ($U->username, $U);
				}
			}
			$Ks->ksort ();

			@\file_put_contents ($file, $Ks->serialize ());
			@\chmod ($file, 0777);
		} else {
			$str = @\file_get_contents ($file);
			$Ks->unserialize ($str);
		}

		return $Ks;
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