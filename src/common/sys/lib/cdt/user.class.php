<?php

namespace CDT;

class User {

	const USER_FILE = '/login-users.txt';

	const ADMIN_FILE = '/login-admins.txt';

	private $user = array();

	private $userCache = array();

	public function login ($requireAdmin = false) {
		$input = Submission::input();

		$valid = !is_null ($input->get ('username')) && !is_null ($input->get ('password')) && $input->get ('password') == $this->generatePassword ($input->get ('username'));
		
		if (!$valid) {
			return false;
		}
		
		$temp = $this->get ($input->get ('username'));
		if ($requireAdmin && !isset ($temp['admin'])) {
			return false;
		}

		$this->user = $temp;

		return count ($temp) > 0;
	}

	public function get ($username = null) {
		if (is_null ($username)) {
			return $this->user;
		} else if (!empty ($this->userCache) && isset ($this->userCache[$username])) {
			return $this->userCache[$username];
		}
		
		$ret = $this->getData (self::USER_FILE, $username);

		if (count ($ret) == 0) {
			$ret = $this->getData (self::ADMIN_FILE, $username);
			$ret['admin'] = 1;
		}

		$this->userCache[$username] = $ret;

		return $ret;
	}

	public function getAll () {
		return array_merge ($this->getData (self::USER_FILE), $this->getData(self::ADMIN_FILE));
	}

	private function getData ($file, $username = null) {
		$ret = array(); $temp = array();
		$file = new \SplFileObject (DIR_USR . $file);
		while (!$file->eof ()) {
			$row = explode (',', $file->fgets());
			if (count ($row) > 1 && (is_null ($username) || $row[2] === $username)) {
				$temp['username'] = trim ($row[2]);
				$temp['name'] = trim ($row[3]);
				$temp['year'] = trim ($row[0]);
				$temp['cohort'] = trim ($row[1]);
				$temp['email'] = trim ($row[4]);
				$temp['latestVersion'] = $this->getLatestVersion ($row[1], $row[2]);

				$ret[$row[2]] = $temp;
			}
		}

		return is_null ($username) || empty ($ret) ? $ret : array_pop ($ret);
	}

	private function getLatestVersion ($cohort, $username) {
		$dir = DIR_DAT . '/'. $cohort . '/' . $username . '/';
		if (is_dir ($dir)) {
			if ($dh = opendir ($dir)) {
				$versions = array();
		        while (($file = readdir ($dh)) !== false) {
		        	if ($file != '.' && $file != '..') {
		        		$versions[] = $file;
		        	}
		        }
		        closedir ($dh);

		        if (count ($versions) > 0) {
				    rsort ($versions, SORT_NUMERIC);
				    return $versions[0];
				}
			}
		}
	}

	public function generatePassword ($username = null) {
		return is_null ($username) ? $this->generatePassword ($this->user['username']) : sha1('h3s9b7smx-2$'. $username);
	}

	public function getWordCount ($username = null) {
		$user = $this->get ($username);
		return !is_null ($user['year']) ? $user['year'] == 1 || $user['year'] == 4 ? 500 : 1500 : 0;
	}

	public function getFunding ($username = null) {
		$oData = Submission::data();
		$user = $this->get ($username);

		if ($user['year'] == 1) {
			$resp = 'This author is supported by the Horizon Centre for Doctoral Training at the University of Nottingham (RCUK Grant No. EP/L015463/1 ) and by the RCUK’s Horizon Digital Economy Research Institute (RCUK Grant No. EP/G065802/1)';	
		} else {
			$resp = 'This author is supported by the Horizon Centre for Doctoral Training at the University of Nottingham (RCUK Grant No. EP/G037574/1) and by the RCUK’s Horizon Digital Economy Research Institute (RCUK Grant No. EP/G065802/1)';
		}

		return $resp;
	}

}