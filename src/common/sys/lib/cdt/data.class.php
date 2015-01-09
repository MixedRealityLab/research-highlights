<?php

namespace CDT;

class Data {

	private $defaultData = array(
				                 'title' 			=> '',
				                 'tweet'			=> '',
				                 'keywords'			=> '',
				                 'industryName'		=> '',
				                 'industryUrl'		=> '',
				                 'text'				=> "# This is about your work\nYour text *can* be easily **formatted** using the [markdown](http://daringfireball.net/projects/markdown/syntax) syntax.\n\n## You can have multiple titles...\n* ...and unordered lists...\n* ...to display many things.\n\nThere are many different ways you can style your text [1].\n\n![Horizon CDT](https://www.porcheron.uk/wp-content/uploads/2014/03/84232-Horizon-CDT-Logos-1ups2-e1395063482640.jpg?897317)\n\n> Please try to adhere to the formatting guidelines [2].",
				                 'references'		=> "1. Smith, J.P. Studying certainty. Science and Culture 9 (1989) 442.\n2. Jones, M.R. Cooking the data? Science News 8 (1990) 878.",
				                 'website'			=> 'http://www.nottingham.ac.uk/~<username>/',
				                 'twitter'			=> '',
				                 'publications'		=> "1. [My Blog Post on Example](http://www.example.com)\n2. Full citation of a journal article"
				                 );

	public function getDefaultData () {
		return $this->defaultData;
	}

	public function get ($username = null, $includeDefaults = true) {
		$oUser = RH::i()->cdt_user;
		$user = $oUser->get ($username);

		$override = array();
		if (isSet ($user['username'])) {

			$dir = DIR_DAT . '/' . $user['cohort'] . '/' . $user['username'] . '/';
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
					    $user['latestVersion'] = $versions[0];
						$dir = $dir . $versions[0] . '/';			    

						foreach ($this->defaultData as $key => $value) {
							$userValue = @file_get_contents ($dir . $key . '.txt');
							if ($userValue !== false) {
								$override[$key] = $userValue;
							}
						}
					}
			    }
			}
		}

		if ($includeDefaults) {
			$ret = array();
			foreach ($this->defaultData as $key => $value) {
				$ret[$key] = $this->scanOutput (!\is_null ($override) && isSet ($override[$key]) ? $override[$key] : $this->defaultData[$key], $username);
			}
		} else {
			$ret = $override;
		}

		return $ret;
	}

	public function scanOutput ($input, $username = null) {
		$oUser = RH::i()->cdt_user;
		$user = $oUser->get ($username);
		$names = \explode (' ', trim ($user['name']));

		$find 		= array('<word-count>',
		                    '<address>',
		                    '<username>',
		                    '<password>',
		                    '<cohort>',
		                    '<first-name>',
		                    '<name>',
		                    '<deadline>',
			              	'<img-dir>');

		$replace 	= array(
		                  	$oUser->getWordCount ($user['username']),
		                    $user['email'],
		                    $user['username'],
		                    $oUser->generatePassword ($user['username']),
		                    $user['cohort'],
		                    $names[0],
		                    $user['name'],
		                    $oUser->getDeadline ($user['username']),
		                 	URI_DATA . '/' . $user['cohort'] . '/' . $user['username'] . '/' . $user['latestVersion'] .'/'
		                 	);

		return str_replace ($find, $replace, $input);
	}

	public function markdownToHtml ($markdown) {
		return \Michelf\Markdown::defaultTransform ($markdown);
	}

}