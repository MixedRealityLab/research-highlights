<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH;

/**
 * Send emails to users within the system using PEAR's `Mail_mime`.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Email implements \RH\Singleton
{

    /** @var string Line endings in the email */
    private $crlf = "\n";

    /** @var string[] Headers common amongst all emails being sent */
    private $headers = array ();

    /** @var Mail PEAR `Mail` factory instance */
    private $smtp;

    /**
     * Construct the `Mail` factory and include the relevant libraries.
     */
    public function __construct()
    {
        $this->headers['X-Mailer'] = VERSION;

        \error_reporting(E_ALL & ~E_STRICT);

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

        $this->smtp = \Mail::factory('smtp', $options);
    }

    /**
     * Set common headers for email sending. These can be replaced at any time.
     *
     * @param string $from From header
     * @param string $replyTo Reply-To header
     * @param string $envelope Email sender as an address only (no name!)
     */
    public function setHeaders($from, $replyTo, $envelope = null)
    {
        $this->headers['From'] = $from;
        $this->headers['Reply-To'] = $replyTo;

        if (!\is_null($envelope)) {
            $this->headers['Return-Path'] = $envelope;
        }
    }

    /**
     * Send an email to a user
     *
     * @see \RH\User\Model::makeSubsts ()
     * @param string $username Username to whom the email should be sent
     * @param string $subject Subject of the email, substitutions are made for  user keywords.
     * @param string $messageHtml HTML representation of the email, substitutions are made for system keywords.
     * @return bool `true` if the message was successfully sent
     * @throws \RH\Error\InvalidInput if the user does not exist or they don't have an email address
     * @throws \RH\Error\SystemError if there was an error sending the email.
     */
    public function send($username, $subject, $messageHtml)
    {
        $cUser = \I::RH_User();

        try {
            $mUser = $cUser->get($username);
        } catch (\RH\Error\NoUser $e) {
            throw new \RH\Error\InvalidInput('Could not find user with username "'. $username .'".');
        }

        try {
            $mAddress = $mUser->email;
        } catch (\RH\Error\NoField $e) {
            throw new \RH\Error\InvalidInput('Could not find email address for user "'. $username .'".');
        }
        $mSubject = $mUser->makeSubsts($subject);
        $mMessageHtml = $mUser->makeSubsts($messageHtml, true);
        $mMessageText = \strip_tags($mMessageHtml);

        $mHeaders = $this->headers;
        $mHeaders['To'] = $mAddress;
        $mHeaders['Subject'] = $mSubject;
        $mHeaders['Date'] = date('r');

        $messageId = \preg_replace('#^https?://#', '', \rtrim(URI_ROOT,'/'));
        $mHeaders['Message-ID'] = '<'. \uniqid() .'@'. $messageId . '>';

        $mime = new \Mail_mime($this->crlf);
        $mime->setTXTBody($mMessageText);
        $mime->setHTMLBody($mMessageHtml);
        $mHeaders = $mime->headers($mHeaders);
        $mBody = $mime->get();

        $mail = $this->smtp->send($mAddress, $mHeaders, $mBody);
        if (\PEAR::isError($mail)) {
            throw new \RH\Error\SystemError('Could not send email to "'. $mAddress .
                '" because: "'. $mail->getMessage() .'"');
        }

        return true;
    }

    /**
     * Send an email to multiple users.
     *
     * @see \RH\User\Model::makeSubsts ()
     * @param string[] $usernames Usernames to whom the email should be sent
     * @param string $subject Subject of the email, substitutions are made for keywords, per user.
     * @param string $messageHtml HTML representation of the email, substitutions are made for keywords, per user.
     * @return bool `true` if all messages was successfully sent
     */
    public function sendAll($usernames, $subject, $messageHtml)
    {
        $ret = true;

        if (!count($usernames)) {
            throw new \RH\Error\InvalidInput('No usernames submitted!');
        }

        foreach ($usernames as $username) {
            $ret &= $this->send($username, $subject, $messageHtml);
        }

        return $ret;
    }
}
