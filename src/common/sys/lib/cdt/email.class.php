<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT;

class Email {

	private $crlf = "\n";

	private $headers = array();

	private $smtp;

	public function __construct() {
		$this->headers['X-Mailer'] = 'CDT-RH/2.2';

		\error_reporting (E_ALL & ~E_STRICT);
		\set_include_path (\get_include_path() . ':/www/cdt/html/PEAR/PEAR');

		require 'Mail.php';
		require 'Mail/mime.php';

		$options = array ('host' => MAIL_HOST, 'port' => MAIL_PORT, 'persist' => true, 'pipelining' => true);
		if (MAIL_AUTH) {
			$options['auth'] = true;
			$options['username'] = MAIL_USER;
			$options['password'] = MAIL_PASS;
		} else {
			$options['auth'] = false;
		}

		$this->smtp = \Mail::factory ('smtp', $options);
	}

	public function setHeaders ($from, $replyTo) {
		$this->headers['From'] = $from;
		$this->headers['Reply-To'] = $replyTo;
	}

	public function send ($username, $subject, $messageText, $messageHtml) {
		$oUser = RH::i()->cdt_user;
		$oData = RH::i()->cdt_data;

		$user = $oUser->get ($username);

		if (empty ($username) || is_null ($user) || empty ($user)) {
			return false;
		}

		$mAddress = $user['email'];
		$mSubject = $oData->scanOutput ($subject, $username);
		$mMessageText = $oData->scanOutput ($messageText, $username);
		$mMessageHtml = $oData->scanOutput ($messageHtml, $username);

		$mHeaders = $this->headers;
		$mHeaders['To'] = $mAddress;
		$mHeaders['Subject'] = $mSubject;

		$mime = new \Mail_mime ($this->crlf);
		$mime->setTXTBody ($mMessageText);
		$mime->setHTMLBody ($mMessageHtml);
		$mHeaders = $mime->headers ($mHeaders);
		$mBody = $mime->get ();

		$mail = $this->smtp->send ($mAddress, $mHeaders, $mBody);
		return !\PEAR::isError ($mail);
	}

	public function sendAll ($usernames, $subject, $messageText, $messageHtml) {
		if (!\is_array ($usernames)) {
			return $this->send ($usernames, $subject, $messageText, $messageHtml);
		}

		$ret = true;
		foreach ($usernames as $username) {
			$ret &= $this->send ($username, $subject, $messageText, $messageHtml);
		}
		return $ret;
	}
}