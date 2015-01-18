<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\Submission;

/**
 * Model for submissions made by users.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Model extends \CDT\Singleton {

	/** @var string Default file name prefix */
	const DEF_FILE_PRE = 'default-';

	/** @var string Default file name suffix */
	const DEF_FILE_SUF = '.txt';

	/** @var \CDT\Submission\Data Submission template */
	private $defaultData;

	/**
	 * @return string[] Default submission template
	 */
	public function getDefaultData () {
		if (\is_null ($this->defaultData)) {
			$oFileReader = $this->rh->cdt_file_reader;

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
			$this->defaultData = new \CDT\Submission\Data ($data);
		}

		return $this->defaultData;
	}

	/**
	 * Retrieve a user's submission
	 * 
	 * @param string $username User's submission to retrieve, if `null`, the 
	 * 	current logged in user's submission is retrieved
	 * @param bool $includeDefaults Use the submission template if the user has
	 * 	not submitted
	 * @return \CDT\Submission\Data
	 */
	public function get ($username = null, $includeDefaults = true) {
		$oUserModel = $this->rh->cdt_user_model;
		$oUser = $oUserModel->get ($username);

		$override = array();
		if (isSet ($oUser->username)) {
			$dir = DIR_DAT . '/' . $oUser->cohort . '/' . $oUser->username . '/';
			if (\is_dir ($dir)) {
				if ($dh = \opendir ($dir)) {
					$versions = array();
					while (($file = \readdir ($dh)) !== false) {
						if ($file != '.' && $file != '..') {
							$versions[] = $file;
						}
					}
					\closedir ($dh);

					if (\count ($versions) > 0) {
						\rsort ($versions, SORT_NUMERIC);
						$oUser->latestVersion = $versions[0];
						$dir = $dir . $versions[0] . '/';				

						// TODO: replace
						foreach ($this->getDefaultData()->toArray() as $key => $value) {
							$userValue = @\file_get_contents ($dir . $key . '.txt');
							if ($userValue !== false) {
								$override[$key] = $oUserModel->makeSubsts ($userValue, $oUser->username);
							}
						}
					}
				}
			}
		}

		if ($includeDefaults) {
			$ret = array();
			// TODO: replace
			foreach ($this->getDefaultData()->toArray () as $k => $v) {
				$ret[$k] = $oUserModel->makeSubsts (!\is_null ($override) && isSet ($override[$k]) ? $override[$k] : $v, $oUser->username);
			}
		} else {
			$ret = $override;
		}

		return new \CDT\Submission\Data ($ret);
	}

	/**
	 * Retrieve a list of keywords
	 * 
	 * @param string $username User's keywords, or all keywords if `null`
	 * @param mixed[] $ret Results of the keyword scan
	 * @param int $total Total number of keywords found
	 * @return \CDT\Submission\Keywords
	 */
	public function getKeywords ($username = null, &$ret = array(), &$total = 0) {
		$oUserModel = $this->rh->cdt_user_model;

		// get keywords
		if (is_null ($username)) {
			$oUsers = $oUserModel->getAll (null, function ($user) {
				return $user->countSubmission;
			});

			foreach ($oUsers as $oUser) {
				$this->getKeywords ($oUser->username, $ret, $total);
			}
		} else {
			$oUser = $oUserModel->get ($username);

			// get keywords
			$latestVersion = $oUserModel->getLatestVersion ($oUser->cohort, $oUser->username);
			$dir = DIR_DAT . '/' . $oUser->cohort . '/' . $oUser->username . '/' . $latestVersion . '/';
			$file = @\file_get_contents ($dir .  'keywords.txt');
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

		return new \CDT\Submission\Keywords ($ret);
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