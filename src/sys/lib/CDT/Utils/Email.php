<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace CDT\Utils;

/**
 * Send emails to users within the system using PEAR's `Mail_mime`.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Email extends \CDT\Singleton {

	/** @var string Line endings in the email */
	private $crlf = "\n";

	/** @var string[] Headers common amongst all emails being sent */
	private $headers = array();

	/** @var Mail PEAR `Mail` factory instance */
	private $smtp;

	/**
	 * Construct the `Mail` factory and include the relevant libraries.
	 */
	public function __construct() {
		$this->headers['X-Mailer'] = VERSION;

		\error_reporting (E_ALL & ~E_STRICT);

		require 'Mail.php';
		require 'Mail/mime.php';

		$options = array ('host' => MAIL_HOST,
		                  'port' => MAIL_PORT,
		                  'persist' => true,
		                  'pipelining' => true);
		if (MAIL_AUTH) {
			$options['auth'] = true;
			$options['username'] = MAIL_USER;
			$options['password'] = MAIL_PASS;
		} else {
			$options['auth'] = false;
		}

		$this->smtp = \Mail::factory ('smtp', $options);
	}

	/**
	 * Set common headers for email sending. These can be replaced at any time.
	 * 
	 * @param string $from From header
	 * @param string $replyTo Reply-To header
	 */
	public function setHeaders ($from, $replyTo) {
		$this->headers['From'] = $from;
		$this->headers['Reply-To'] = $replyTo;
	}

	/**
	 * Send an email to a user
	 * 
	 * @see CDT\User\Model::makeSubsts()
	 * @param string $username Username to whom the email should be sent
	 * @param string $subject Subject of the email, substitutions are made for 
	 * 	user keywords.
	 * @param string $messageText Text representation of the email, 
	 * 	substitutions are made for system keywords.
	 * @param string $messageHtml HTML representation of the email, 
	 * 	substitutions are made for system keywords.
	 * @return bool `true` if the message was successfully sent
	 */
	public function send ($username, $subject, $messageText, $messageHtml) {
		$oUserController = \CDT\RH::i()->cdt_user_controller;

		$oUser = $oUserController->get ($username);

		if (empty ($username) || \is_null ($oUser)) {
			return false;
		}

		$mAddress = $oUser->email;
		$mSubject = $oUser->makeSubsts ($subject);
		$mMessageText = $oUser->makeSubsts ($messageText);
		$mMessageHtml = $oUser->makeSubsts ($messageHtml);

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

	/**
	 * Send an email to multiple users.
	 * 
	 * @see CDT\User\Model::makeSubsts()
	 * @param string[] $usernames Usernames to whom the email should be sent
	 * @param string $subject Subject of the email, substitutions are made for 
	 * 	system keywords, per user.
	 * @param string $messageText Text representation of the email, 
	 * 	substitutions are made for system keywords, per user.
	 * @param string $messageHtml HTML representation of the email, 
	 * 	substitutions are made for system keywords, per user.
	 * @return bool `true` if all messages was successfully sent
	 */
	public function sendAll ($usernames, $subject, $messageText, $messageHtml) {
		$ret = true;
		foreach ($usernames as $u) {
			$ret &= $this->send ($u, $subject, $messageText, $messageHtml);
		}

		return $ret;
	}
}