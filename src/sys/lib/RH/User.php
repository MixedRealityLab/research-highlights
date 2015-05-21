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
class User implements \RH\Singleton {

	/** @var string File name for standard users who can log in */
	const USER_FILE = '/login-users.csv';

	/** @var string File name for administrative users who can log in */
	const ADMIN_FILE = '/login-admins.csv';

	/** @var string File name for funding statements */
	const FUNDING_FILE = '/funding.csv';

	/** @var string File name for word counts */
	const WORD_COUNT_FILE = '/wordCount.csv';

	/** @var string File name for submission deadlines */
	const DEADLINES_FILE = '/deadlines.csv';

	/** @var string Users model cache */
	const USERS_CACHE = 'users.cache';

	/** @var string User model cache */
	const USER_CACHE = 'user-%s.cache';

	/** @var string User Email cache */
	const USER_EMAILS_CACHE = 'userEmails.cache';

	/** @var string Funding statements model cache */
	const FUNDING_CACHE = 'fundingStatements.cache';

	/** @var string Deadlines model cache */
	const DEADLINE_CACHE = 'deadline.cache';

	/** @var string Word Count model cache */
	const WORD_COUNT_CACHE = 'wordCount.cache';

	/** @var string Cohorts model cache */
	const COHORT_CACHE = 'cohorts.cache';

	/** @var \RH\Model\User Currently logged in user */
	private $mUser = null;

	/** @var \RH\Model\Users Cache of user details */
	private $mUsers;

	/** @var \RH\Model\UserEmails Cache of user details */
	private $mUserEmails;

	/** @var bool Does `$mUsers` store all users? */
	private $mUsersAll = false;

	/** @var \RH\Model\FundingStatements Cache of funding statements */
	private $mFundingStatements;

	/** @var \RH\Model\Deadlines Cache of deadline statements */
	private $mDeadlines;

	/** @var \RH\Model\WordCounts Cache of word counts */
	private $mWordCounts;

	/** @var \RH\Model\Cohorts Cache of cohorts */
	private $mCohorts;

	public function __construct () {
		$this->mUsers = new \RH\Model\Users ();
	}

	/**
	 * Log a user into the system.
	 *
	 * @param string $mUsername Username to login with.
	 * @param string $password Password to use to login with.
	 * @param bool $requireAdmin if `true`, is an administrator account required
	 * @return the \RH\Model\User object
	 * @throws \RH\Error\NoUser if the account is disabled
	 * @throws \RH\Error\NotAuthorised if an admin account is required and the
	 * 	login request is for a non-admin account
	 * @throws \RH\Error\AccountDisabled if the account is disabled
	 */
	public function login ($mUsername, $password, $requireAdmin = false) {
		try {
			$temp = $this->get (\strtolower ($mUsername));

			if ($password !== $temp->getPassword ()) {
				throw new \RH\Error\NoUser ();
			}

			if ($requireAdmin && !$temp->admin) {
				throw new \RH\Error\NotAuthorised ();
			}

			if (!$temp->enabled) {
				throw new \RH\Error\AccountDisabled ();
			}

			$this->user = $temp;

			return $this->user;
		} catch (\InvalidArgumentException $e) {
			throw new \RH\Error\NoUser ();
		}
	}

	/**
	 * Allow a user to masquerade as another user (must be currently logged in
	 * as an administrator).
	 *
	 * @param \RH\Model\User $mUser User of the person who we are going to
	 * 	pretend to be.
	 * @throws \RH\Error\NotAuthorised if an admin account is required and the
	 * 	login request is for a non-admin account
	 * @throws \RH\Error\NoUser if the account is disabled
	 * @return \RH\Model\User new User object
	 */
	public function overrideLogin (\RH\Model\User $mUser) {
		$mInput = \I::RH_Model_Input ();

		if (!isSet ($this->user->admin)) {
			throw new \RH\Error\NotAuthorised ();
		}

		$this->user = $u;
		$this->mFundingStatements = null;
		$this->mDeadlines = null;
		$this->mWordCounts = null;
		return $this->user;
	}

	/**
	* Retrieve the details of a user.
	*
	* @param string|null $mUser User to retrieve full details for, or
	* 	if `null`, retrieve the currently logged in user, or if a User object,
	* 	the function will return this object.
	* @return \RH\Model\User Details of the user
	*/
	public function get ($user = null) {
		if (\is_null ($user)) {
			return $this->mUser;
		} else if ($user instanceof \RH\Model\User) {
			return $user;
		} else {
			$user = \strtolower ($user);

			if (!isSet ($this->mUsers->$user)) {
				$file = \sprintf (self::USER_CACHE, $user);

				$mUser = new \RH\Model\User ();
				$mUser->setCache (CACHE_USER, $file);

				if ($mUser->hasCache ()) {
					$this->mUsers->$user = $mUser->loadCache ();
				} else {
					$this->getAll ();
					$this->mUsers->$user->setCache (CACHE_USER, $file);
					$this->mUsers->$user->saveCache ();
				}
			}

			if (!isSet ($this->mUsers->$user)) {
				return null;
			}

			return $this->mUsers->$user;
		}
	}

	/**
	 * Retrieve the details of a user.
	 *
	 * @param string $email Email address of a user to get.
	 * @return \RH\Model\User Details of the user
	 */
	public function getByEmail ($email) {
		if (is_null ($this->mUserEmails)) {
			$this->getAll ();
		}

		$email = \strtolower ($email);

		if (!isSet ($this->mUserEmails[$email])) {
			throw new \RH\Error\NoUser ();
		}

		return $this->get ($this->mUserEmails[$email]);
	}

	/**
	 * Retrieve the details of all users.
	 *
	 * @param function|null $sortFn How to sort the user list; if `null`, reverse
	 * 	sort by cohort, then sort by name
	 * @param function|null $filterFn How to filter the user list; if `null`, all
	 * 	users are included
	 * @return \RH\Model\Users Data on all requested users.
	 */
	public function getAll ($sortFn = null, $filterFn = null) {
		if (\is_null ($sortFn)) {
			$sortFn = function ($a, $b) {
				if ($a->cohort === $b->cohort) {
					if ($a->surname === $b->surname) {
						return \strcmp ($a->firstName, $b->firstName);
					} else {
						return \strcmp ($a->surname, $b->surname);
					}
				} else {
					return \strcmp ($b->cohort, $a->cohort);
				}
			};
		}

		if (\is_null ($filterFn)) {
			$filterFn = function ($cUser) {
				return true;
			};
		}

		if (!$this->mUsersAll) {
			$mUsers = new \RH\Model\Users ();
			$mUsers->setCache (CACHE_USER, self::USERS_CACHE);

			$mUserEmails = new \RH\Model\UserEmails ();
			$mUserEmails->setCache (CACHE_USER, self::USER_EMAILS_CACHE);

			if ($mUsers->hasCache () && $mUserEmails->hasCache ()) {
				$mUsers->loadCache ();
				$mUserEmails->loadCache ();
			} else {
				$adminUsers = $this->getData (self::ADMIN_FILE);
				foreach ($adminUsers as $mUser) {
					$mUser->admin = true;
				}

				$mUsers->merge ($adminUsers);
				$mUsers->merge ($this->getData (self::USER_FILE));

				foreach ($mUsers as $mUser) {
					$mUser->deadline = $this->getDeadline ($mUser);
					$mUser->wordCount = $this->getWordCount ($mUser);
					$mUser->fundingStatement = $this->getFunding ($mUser);
					if (!isSet ($mUser->admin)) {
						$mUser->admin = false;
					}

					$mUser->username = \strtolower ($mUser->username);

					$email = \strtolower ($mUser->email);
					$mUserEmails[$email] = $mUser->username;
				}

				$mUsers->saveCache ();
				$mUserEmails->saveCache ();
			}

			$this->mUsers = $mUsers;
			$this->mUsersAll = true;
			$this->mUserEmails = $mUserEmails;
		}

		$mUsers = clone $this->mUsers;
		$mUsers->uasort ($sortFn);
		$mUsers->filter ($filterFn);

		return $mUsers;
	}

	/**
	 * Retrieve the cohorts.
	 *
	 * @param function|null $sortFn How to sort the cohort list; if `null`,
	 * 	reverse sort by cohort
	 * @param function|null $filterFn How to filter the cohort list; if `null`, all
	 * 	cohort are included
	 * @return string[] Array of details of the cohorts
	 */
	public function getCohorts ($sortFn = null, $filterFn = null) {
		if (\is_null ($sortFn)) {
			$sortFn = function ($a, $b) {
				return \strcmp ($b, $a);
			};
		}

		if (\is_null ($filterFn)) {
			$filterFn = function ($cohort) {
				return true;
			};
		}

		if (\is_null ($this->mCohorts)) {
			$mCohorts = new \RH\Model\Cohorts ();
			$mCohorts->setCache (CACHE_USER, self::COHORT_CACHE);

			if ($mCohorts->hasCache ()) {
				$mCohorts->loadCache ();
			} else {
				$mUsers = $this->getAll ();
				foreach ($mUsers as $mUser) {
					$cohort = $mUser->cohort;
					if (!isSet ($mCohorts->$cohort)) {
						$mCohorts->$cohort = $cohort;
					}
				}

				$mCohorts->saveCache ();
			}

			$this->mCohorts = $mCohorts;
		}

		$this->mCohorts->filter ($filterFn);
		$this->mCohorts->uasort ($sortFn);

		return $this->mCohorts;
	}

	/**
	 * Retrieve a user's data from a file, or all users.
	 *
	 * @param string $file File to get the user's data from.
	 * @param string $username Username of the user to retrieve, or `null` to
	 * 	get all users in the file.
	 * @return \RH\Model\Users|\RH\Model\User Details of the user (s).
	 */
	private function getData ($file, $username = null) {
		$readRowFn = function ($cols) use ($username) {
			return \is_null ($username) || $cols[2] === $username || $cols[5] === $username;
		};
		$calcValuesFn = function (&$data, $cols) {
			// get the latest version
			$dir = DIR_DAT . '/'. $cols[1] . '/' . $cols[2];
			$data['dir'] = $dir;
			$versions = \glob ($dir . '/*', GLOB_ONLYDIR);

			if (empty ($versions)) {
				$data['latestVersion'] = false;
			} else {
				$data['latestVersion'] = \str_replace ($dir . '/', '', \end ($versions));
				$data['latestSubmission'] = $dir . '/' . $data['latestVersion'];
			}
		};

		$oFileReader = \I::RH_File_Reader ();
		$data = $oFileReader->read (DIR_USR . $file, 'username', $readRowFn, $calcValuesFn);

		if (!\is_null ($username) && !isSet ($data[$username])) {
			return false;
		}

		return \is_null ($username) || \count ($data) != 1
			? new \RH\Model\Users ($data)
			: new \RH\Model\User (\array_pop ($data));
	}

	/**
	 * Retrieve the word count for a particular user.
	 *
	 * @param \RH\Model\User $mUser User to retrieve the word count
	 * 	for, if `null`, gets the currently logged in user
	 * @return string Word count of the user
	 */
	private function getWordCount (\RH\Model\User $mUser = null) {
		if (\is_null ($this->mWordCounts)) {
			$mWordCounts = new \RH\Model\WordCounts ();
			$mWordCounts->setCache (CACHE_USER, self::WORD_COUNT_CACHE);

			if ($mWordCounts->hasCache ()) {
				$mWordCounts->loadCache ();
			} else {
				$oFileReader = \I::RH_File_Reader ();
				$data = $oFileReader->read (DIR_USR . self::WORD_COUNT_FILE, 'cohort');
				$mWordCounts->merge ($data)->saveCache ();
			}

			$this->mWordCounts = $mWordCounts;
		}

		$cohort = \is_null ($mUser)
			? $this->user->cohort
			: $mUser->cohort;

		return $this->mWordCounts->$cohort->wordCount;
	}

	/**
	 * Retrieve the funding statement for a particular user.
	 *
	 * @param \RH\Model\User $mUser User to retrieve the funding statement for, if
	 * 	`null`, gets the currently logged in user
	 * @return string Funding statement of the user
	 */
	private function getFunding (\RH\Model\User $mUser = null) {
		if (\is_null ($this->mFundingStatements)) {
			$mFundingStatements = new \RH\Model\FundingStatements ();
			$mFundingStatements->setCache (CACHE_USER, self::FUNDING_CACHE);

			if ($mFundingStatements->hasCache ()) {
				$mFundingStatements->loadCache ();
			} else {
				$oFileReader = \I::RH_File_Reader ();
				$data = $oFileReader->read (DIR_USR . self::FUNDING_FILE, 'fundingStatementId');
				$mFundingStatements->merge ($data)->saveCache ();
			}

			$this->mFundingStatements = $mFundingStatements;
		}

		$id = \is_null ($mUser)
			? $this->user->fundingStatementId
			: $mUser->fundingStatementId;

		return $this->mFundingStatements->$id->fundingStatement;
	}

	/**
	 * Retrieve the deadline for a particular user.
	 *
	 * @param \RH\Model\User $mUser User to retrieve the deadline for, if `null`,
	 * 	gets the currently logged in user
	 * @return string Deadline of the user
	 */
	private function getDeadline (\RH\Model\User $mUser = null) {
		if (\is_null ($this->mDeadlines)) {
			$mDeadlines = new \RH\Model\Deadlines ();
			$mDeadlines->setCache (CACHE_USER, self::DEADLINE_CACHE);

			if ($mDeadlines->hasCache ()) {
				$mDeadlines->loadCache ();
			} else {
				$oFileReader = \I::RH_File_Reader ();
				$data = $oFileReader->read (DIR_USR . self::DEADLINES_FILE, 'cohort');
				$mDeadlines->merge ($data)->saveCache ();
			}

			$this->mDeadlines = $mDeadlines;
		}

		$cohort = \is_null ($mUser)
			? $this->user->cohort
			: $mUser->cohort;

		return $this->mDeadlines->$cohort->deadline;
	}
}
