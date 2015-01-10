<?php

namespace CDT;

class User {

	const USER_FILE = '/login-users.txt';

	const ADMIN_FILE = '/login-admins.txt';

	const FUNDING_FILE = '/funding.txt';

	const DEADLINES_FILE = '/deadlines.txt';

	private $user = array();

	private $userCache = array();

	private $fundingCache = array();

	private $deadlineCache = array();

	public function login ($requireAdmin = false) {
		$oInput = RH::i()->cdt_input;

		$username = \strtolower ($oInput->get ('username'));
		$valid = !\is_null ($username) && !\is_null ($oInput->get ('password')) && $oInput->get ('password') == $this->generatePassword ($username);
		
		if (!$valid) {
			return false;
		}
		
		$temp = $this->get ($username);
		if ($requireAdmin && !isSet ($temp['admin'])) {
			return false;
		}

		if (!$temp['enabled']) {
			return false;
		}

		$this->user = $temp;

		return \count ($temp) > 0;
	}

	public function overrideLogin ($username) {
		$oInput = RH::i()->cdt_input;

		$newUser = $this->get (\strtolower ($username));
		if (isSet ($this->user['admin']) && !empty ($newUser)) {
			$this->user = $newUser;
			$this->fundingCache = array();
			$this->deadlineCache = array();
		}
	}

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
				$ret['admin'] = JSF_ADMIN;
			}
		}

		$this->userCache[$username] = $ret;

		return $ret;
	}

	public function getAll ($sort = null, $filter = null) {
		if (\is_null ($sort)) {
			$sort = function ($a, $b) {
				if ($a['cohort'] === $b['cohort']) {
					return \strcmp ($a['name'], $b['name']);
				} else {
					return \strcmp ($b['cohort'], $a['cohort']);
				}
			};
		}

		if (\is_null ($filter)) {
			$filter = function ($user) {
				return true;
			};
		}

		$users = \array_merge ($this->getData (self::USER_FILE), $this->getData (self::ADMIN_FILE));
		$users = \array_filter ($users, $filter);
		\usort ($users, $sort);

		return $users;
	}

	private function getData ($file, $username = null) {
		$oFileReader = RH::i()->cdt_file_reader;

		$readRowFn = function ($cols) use ($username) {
			return \is_null ($username) || $cols[2] === $username;
		};
		$calcValuesFn = function (&$data, $cols) {
			$data['latestVersion'] = $this->getLatestVersion ($cols[1], $cols[2]);
		};

		$data = $oFileReader->read (DIR_USR . $file, 'username', $readRowFn, $calcValuesFn);
		return \is_null ($username) || empty ($data) ? $data : array_pop ($data);
	}

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

	public function generatePassword ($username = null) {
		return \is_null ($username) ? $this->generatePassword ($this->user['username']) : \sha1 (SALT . $username);
	}

	public function getWordCount ($username = null) {
		$user = $this->get ($username);
		return !\is_null ($user['year']) ? $user['year'] == 1 || $user['year'] == 4 ? 500 : 1500 : 0;
	}

	public function getFunding ($username = null) {
		$user = $this->get ($username);

		if (empty ($this->fundingCache)) {
			$oFileReader = RH::i()->cdt_file_reader;
			$this->fundingCache = $oFileReader->read (DIR_USR . self::FUNDING_FILE, 'fundingStatementId');
		}

		return $this->fundingCache[$user['fundingStatementId']]['fundingStatement'];
	}

	public function getDeadline ($username = null) {
		$user = $this->get ($username);

		if (empty ($this->deadlineCache)) {
			$oFileReader = RH::i()->cdt_file_reader;
			$this->deadlineCache = $oFileReader->read (DIR_USR . self::DEADLINES_FILE, 'cohort');
		}

		return $this->deadlineCache[$user['cohort']]['deadline'];
	}
}