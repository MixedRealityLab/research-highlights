<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * List of keywords, and the user submissions which have used then.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class SearchKeywords extends AbstractModel {

	/**
	 * @return mixedp[]
	 */
	private static function getWeights() {
		$importance							= array ();
		$importance['author']				= 100;
		$importance['keyword']				= 40;
		$importance['title']				= 10;
		$importance['text']['h1']			= 9;
		$importance['text']['h2']			= 8;
		$importance['text']['h3']			= 7;
		$importance['text']['h4']			= 6;
		$importance['text']['strong']		= 5;
		$importance['text']['em']			= 3;
		$importance['text']['blockquote']	= 3;
		$importance['text']['text']			= 1;

		$use								= array ();
		$use['author']						= 1;
		$use['keyword']						= .95;
		$use['title']						= .96;
		$use['text']['h1']					= .97;
		$use['text']['h2']					= .98;
		$use['text']['h3']					= .99;
		$use['text']['h4']					= 1;
		$use['text']['strong']				= .99;
		$use['text']['em']					= .98;
		$use['text']['blockquote']			= .97;
		$use['text']['text']				= .96;

		return array ('imp' => $importance, 'use' => $use);
	}

	/**
	 * Create a new keyword within this list.
	 * 
	 * @param mixed $value Keyword data.
	 * @return \RH\Model\SearchKeyword New search keyword object.
	 */
	protected function newChild ($value) {
		return new SearchKeyword ($value);
	}

	/**
	 * Add a series of space-separated keywords to the index
	 * 
	 * @param string[] $arr Array of keywords to append
	 * @param \RH\Model\User $mUser User responsible
	 * @param \RH\Model\Submission $mSubmission Submission to add
	 * @param string $keywords Space-separated keywords to individually add to 
	 * 	$arr
	 * @param float $importance Importance factor for each keyword
	 * @param float $use Usage multiple (the importance factor is times by this 
	 * 	to the power of the number of occurrences of this keyword) before being
	 * 	added
	 */
	private function appendIndex ($keywords, \RH\Model\User $mUser, \RH\Model\Submission $mSubmission, $importance, $use) {
		$keywords =  \preg_replace ('/[^a-z0-9 ]+/i', '', \strtolower ($keywords));
		$keywords = \preg_split ('/\s+/', $keywords, null, PREG_SPLIT_NO_EMPTY);
		foreach ($keywords as $keyword) {
			if (!$this->offsetExists ($keyword)) {
			 	$this->offsetSet ($keyword, new \RH\Model\SearchKeyword());
			}

			$this->$keyword->users[$mUser->username] = $mUser;
			$this->$keyword->submissions[$mUser->username] = $mSubmission;
			$this->$keyword->importance += $importance * \pow ($use, \count ($this->$keyword->users));
		}
	}


	/**
	 * Add a user and submission to the search keywords database.
	 * 
	 * @param \RH\Model\User $mUser User to add
	 * @param \RH\Model\Submission $mSubmission Submission to add
	 */
	public function add (\RH\Model\User $mUser, \RH\Model\Submission $mSubmission) {
		$weights = self::getWeights ();

		$this->appendIndex ($mUser->firstName, $mUser, $mSubmission,
		                    $weights['imp']['author'],
		                    $weights['use']['author']);

		$this->appendIndex ($mUser->surname, $mUser, $mSubmission,
		                    $weights['imp']['author'],
		                    $weights['use']['author']);

		$keywords = \preg_split ('/,+/', $mSubmission->keywords, null, PREG_SPLIT_NO_EMPTY);
		foreach ($keywords as $keyword) {
			$this->appendIndex ($keyword, $mUser, $mSubmission,
			                    $weights['imp']['author'],
			                    $weights['use']['keyword']);
		}

		$this->appendIndex ($mSubmission->title, $mUser, $mSubmission,
		                    $weights['imp']['author'],
		                    $weights['use']['title']);

		$text = $mUser->makeSubsts ($mSubmission->text);
		$text = \RH\Submission::markdownToHtml ($text);

		$tags = array('h1', 'h2', 'h3', 'h4', 'strong', 'em', 'blockquote');
		foreach ($tags as $tag) {
			$matches = array();
			\preg_match_all ("/<$tag>(.*?)<\/$tag>/", $text, $matches, PREG_PATTERN_ORDER);
			
			foreach ($matches[1] as $keywords) {
				$this->appendIndex ($keywords, $mUser, $mSubmission,
				                    $weights['imp']['text'][$tag],
				                    $weights['use']['text'][$tag]);
			}
		}

		$this->appendIndex ($text, $mUser, $mSubmission,
		                    $weights['imp']['text']['text'],
		                    $weights['use']['text']['text']);
	}

}