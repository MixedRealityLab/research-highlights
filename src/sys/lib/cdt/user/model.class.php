<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\User;

/**
 * Model for submissions made by users.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Model extends \CDT\Singleton {

	/** @var string File name for standard users who can log in */
	const USER_FILE = '/login-users.txt';

	/** @var string File name for administrative users who can log in */
	const ADMIN_FILE = '/login-admins.txt';

	/** @var string File name for funding statements */
	const FUNDING_FILE = '/funding.txt';

	/** @var string File name for word counts */
	const WORD_COUNT_FILE = '/wordCount.txt';

	/** @var string File name for submission deadlines */
	const DEADLINES_FILE = '/deadlines.txt';

	/** @var \CDT\User\Data Currently logged in user */
	private $user = array();

	/** @var \CDT\User\Data[] Cache of user details */
	private $userCache = array();

	/** @var string[] Cache of funding statements */
	private $fundingCache = array();

	/** @var string[] Cache of deadline statements */
	private $deadlineCache = array();

	/** @var int[] Cache of word counts */
	private $wordCountCache = array();

	/**
	 * Log a user into the system.
	 * 
	 * @param bool $requireAdmin if `true`, is an administrator account required
	 * @return bool `true` if the login was successful
	 */
	public function login ($requireAdmin = false) {
		$oInputModel = $this->rh->cdt_input_model;

		$username = \strtolower ($oInputModel->get ('username'));
		$valid = !\is_null ($username) && !\is_null ($oInputModel->get ('password')) && $oInputModel->get ('password') == $this->generatePassword ($username);
		
		if (!$valid) {
			return false;
		}
		
		$temp = $this->get ($username);
		if ($requireAdmin && !isSet ($temp->admin)) {
			return false;
		}

		if (!$temp->enabled) {
			return false;
		}

		$this->user = $temp;

		return \count ($temp) > 0;
	}

	/**
	 * Allow a user to masquerade as another user (must be currently logged in
	 * as an administrator).
	 * 	
	 * @param string $username Username of the person who we are going to 
	 * 	pretend to be.
	 * @return bool `true` if successful
	 */
	public function overrideLogin ($username) {
		$oInputModel = $this->rh->cdt_input_model;

		$newUser = $this->get (\strtolower ($username));
		if (isSet ($this->user->admin) && !empty ($newUser)) {
			$this->user = $newUser;
			$this->fundingCache = array();
			$this->deadlineCache = array();
			return true;
		}

		return false;
	}

	/**
	 * Retrieve the details of a user.
	 * 
	 * @param string|null $username Username to retrieve full details for, or 
	 * 	if `null`, retrieve the currently logged in user
	 * @return \CDT\User\Data Details of the user
	 */
	public function get ($username = null) {
		if (\is_null ($username)) {
			return $this->user;
		} else if (isSet ($this->userCache[$username])) {
			return $this->userCache[$username];
		}
		
		$ret = $this->getData (self::USER_FILE, $username);

		if (\count ($ret) == 0) {
			$ret = $this->getData (self::ADMIN_FILE, $username);
			if (count ($ret) > 0) {
				$ret->admin = 'true'; // TODO: break this
			}
		}

		$this->userCache[$username] = $ret;

		return $ret;
	}

	/**
	 * Retrieve the details of all users.
	 * 
	 * @param function|null $sort How to sort the user list; if `null`, reverse 
	 * 	sort by cohort, then sort by name
	 * @param function|null @filter How to filter the user list; if `null`, all
	 * 	users are included
	 * @return \CDT\User\Data[] Array of details of the users
	 */
	public function getAll ($sort = null, $filter = null) {
		if (\is_null ($sort)) {
			$sort = function ($a, $b) {
				if ($a->cohort === $b->cohort) {
					return \strcmp ($a->name, $b->name);
				} else {
					return \strcmp ($b->cohort, $a->cohort);
				}
			};
		}

		if (\is_null ($filter)) {
			$filter = function ($oUser) {
				return true;
			};
		}

		$users = \array_merge ($this->getData (self::USER_FILE), $this->getData (self::ADMIN_FILE));
		$users = \array_filter ($users, $filter);
		\usort ($users, $sort);

		return $users;
	}

	/**
	 * Retrieve the cohorts.
	 * 
	 * @param function|null $sort How to sort the cohort list; if `null`,
	 * 	reverse sort by cohort
	 * @param function|null @filter How to filter the cohort list; if `null`, all
	 * 	cohort are included
	 * @return string[] Array of details of the cohorts
	 */
	public function getCohorts ($sort = null, $filter = null) {
		if (\is_null ($sort)) {
			$sort = function ($a, $b) {
				return \strcmp ($b, $a);
			};
		}

		if (\is_null ($filter)) {
			$filter = function ($cohort) {
				return true;
			};
		}

		$users = \array_merge ($this->getData (self::USER_FILE), $this->getData (self::ADMIN_FILE));
		$cohorts = array();
		foreach ($users as $user) {
			if (!\in_array ($user->cohort, $cohorts)) {
				$cohorts[] = $user->cohort;
			}
		}

		$cohorts = \array_filter ($cohorts, $filter);
		\usort ($cohorts, $sort);

		return $cohorts;
	}

	/**
	 * Retrieve a user's data from a file, or all users.
	 * 
	 * @param string $file File to get the user's data from.
	 * @param string $username Username of the user to retrieve, or `null` to 
	 * 	get all users in the file.
	 * @return \CDT\User\Data|\CDT\User\Data[] Details of the user(s).
	 */
	private function getData ($file, $username = null) {
		$oFileReader = $this->rh->cdt_file_reader;

		$readRowFn = function ($cols) use ($username) {
			return \is_null ($username) || $cols[2] === $username;
		};
		$calcValuesFn = function (&$data, $cols) {
			$data['latestVersion'] = $this->getLatestVersion ($cols[0], $cols[2]);
		};

		$data = $oFileReader->read (DIR_USR . $file, 'username', $readRowFn, $calcValuesFn);
		$oData = \CDT\User\Data::fromArrays ($data);

		return \is_null ($username) || empty ($data) ? $oData : array_pop ($oData);
	}

	/**
	 * Get the ID of the latest submission of a user
	 * 
	 * @param string $cohort Cohort of the user
	 * @param string $username Username of the user to retrieve the word count 
	 * 	for
	 * @return string Word count of the user
	 */
	private function getLatestVersion ($cohort, $username) {
		$dir = DIR_DAT . '/'. $cohort . '/' . $username . '/';
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
					rsort ($versions, SORT_NUMERIC);
					return $versions[0];
				}
			}
		}
	}

	/**
	 * Generate the user's password
	 * 
	 * @param string $username Username of the user to generate the password
	 * 	for, if `null`, gets the currently logged in user
	 * @return string Password of the user
	 */
	public function generatePassword ($username = null) {
		return \is_null ($username) ? $this->generatePassword ($this->user->username) : \sha1 (SALT . $username);
	}

	/**
	 * Retrieve the word count for a particular user.
	 * 
	 * @param string $username Username of the user to retrieve the word count 
	 * 	for, if `null`, gets the currently logged in user
	 * @return string Word count of the user
	 */
	public function getWordCount ($username = null) {
		$oUser = $this->get ($username);

		if (empty ($this->wordCountCache)) {
			$oFileReader = $this->rh->cdt_file_reader;
			$this->wordCountCache = $oFileReader->read (DIR_USR . self::WORD_COUNT_FILE, 'cohort');
		}

		return $this->wordCountCache[$oUser->cohort]['wordCount'];
	}

	/**
	 * Retrieve the funding statement for a particular user.
	 * 
	 * @param string $username Username of the user to retrieve the funding 
	 * 	statement for, if `null`, gets the currently logged in user
	 * @return string Funding statement of the user
	 */
	public function getFunding ($username = null) {
		$user = $this->get ($username);

		if (empty ($this->fundingCache)) {
			$oFileReader = $this->rh->cdt_file_reader;
			$this->fundingCache = $oFileReader->read (DIR_USR . self::FUNDING_FILE, 'fundingStatementId');
		}

		return $this->fundingCache[$user->fundingStatementId]['fundingStatement'];
	}

	/**
	 * Retrieve the deadline for a particular user.
	 * 
	 * @param string $username Username of the user to retrieve the deadline
	 * 	for, if `null`, gets the currently logged in user
	 * @return string Deadline of the user
	 */
	public function getDeadline ($username = null) {
		$user = $this->get ($username);

		if (empty ($this->deadlineCache)) {
			$oFileReader = $this->rh->cdt_file_reader;
			$this->deadlineCache = $oFileReader->read (DIR_USR . self::DEADLINES_FILE, 'cohort');
		}

		return $this->deadlineCache[$user->cohort]['deadline'];
	}

	/**
	 * Get a list of all the substitutions that can be made.
	 * 
	 * @param string $username User to whom the output pertains, if `null`, 
	 * 	the current logged in user is used
	 * @return string[] Text and substituted values as an associate array
	 */
	private function substs ($username = null) {
		$fandr = array();
		if (\is_null ($username)) {
		$oFileReader = $this->rh->cdt_file_reader;
			$header = $oFileReader->readHeader (DIR_USR . self::USER_FILE);
			foreach ($header->toArray() as $col) {
				$fandr['<' . $col->name .'>'] = '';
			}
		} else {
			$oUser = $this->get ($username);
			foreach ($oUser->toArray () as $k => $v) {
				$fandr['<' . $k .'>'] = $v;
			}
		}

		$fandr['<password>'] = \is_null ($username) ? '' : $this->generatePassword ($username);
		$fandr['<wordCount>'] = \is_null ($username) ? '' : $this->getWordCount ($username);
		$fandr['<deadline>'] = \is_null ($username) ? '' : $this->getDeadline ($username);
		$fandr['<fundingStatement>'] = \is_null ($username) ? '' : $this->getFunding ($username);
		$fandr['<imgDir>'] = \is_null ($username) ? ''
			: URI_DATA . '/' . $oUser->cohort . '/' . $oUser->username . '/' . $oUser->latestVersion .'/';

		return $fandr;
	}

	/**
	 * List of possible substitutions.
	 * 
	 * @param string $username User to whom the output pertains, if `null`, 
	 * 	the current logged in user is used
	 * @return string[] List of possible substitutions
	 */
	public function substsKeys ($username = null) {
		return \array_keys ($this->substs ($username));
	}

	/**
	 * Scan text for keywords that can be replaced. These keywords are currently
	 * hardcoded.
	 * 
	 * @param string $input Input to be scanned
	 * @param string $username User to whom the output pertains, if `null`, 
	 * 	the current logged in user is used
	 * @return string Output with the substitutions made
	 */
	public function makeSubsts ($input, $username = null) {
		$fandr = $this->substs ($username);
		return \str_replace (\array_keys ($fandr), 
		                     \array_values ($fandr), $input);
	}
}