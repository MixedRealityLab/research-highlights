<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$cTemplate = I::RH_Template();
$cUser = I::RH_User();

$cTemplate->startCapture();

?>
			<div class="visible-xs visible-sm">
				<div class="alert alert-danger"><div class="container"><div class="row">Your screen is small and this will make editing your input difficult. It is recommended you use a desktop/laptop while sending emails.</div></div></div>
			</div>
			<div class="container main">
				<div class="row main">

					<!-- BEGIN LOGIN STAGE -->
					<form class="stage stage-login collapse">
						<div class="card-container">
							<div class="card">
								<div class="card-title">Email Users</div>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
									<input type="text" class="form-control" name="editor" id="editor" placeholder="University username" spellcheck="false">
								</div>
								<br>
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
									<input type="password" class="form-control" name="password" id="password" placeholder="Password" spellcheck="false">
								</div>
								<br>
								<button class="btn btn-lg btn-primary btn-block btn-success" type="submit" id="verify">Login</button>
							</div>
							<div class="card-trailer">
								Have you <a href="<?php print URI_HOME; ?>/forgotten" title="Password Reminder">forgotten your password</a>?
							</div>
						</div>
					</form>
					<!-- END LOGIN STAGE -->

					<!-- BEGIN EMAIL STAGE -->
					<form class="stage stage-email collapse">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<p>You can use any of the following codes, these are substituted per user at email time: <?php $keys = '';
                            foreach (\RH\Model\User::substsKeys() as $key) :
                                $keys .= (empty($keys) ? '' : ', ') . '<code>'. \htmlentities($key) .'</code>';

                            endforeach;
                            print $keys; ?>.</p>
							<hr>
						</div>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h3 class="panel-title">Send Emails</h3>
								</div>
								<input type="hidden" name="username" id="submit-user">
								<input type="hidden" name="password" id="submit-pass">
								<div class="panel-body">
									<div class="form-group">
										<label for="title">Subject</label>
										<input type="text" class="form-control input input-large" autocomplete="off" name="subject" id="subject" placeholder="Subject for the email" value="[IMPORTANT] Horizon CDT Research Highlights: Login Details for <firstName> <surname>">
									</div>
									<hr>
									<div class="form-group">
										<label for="title">Message</label>
										<textarea name="message" id="message" rows="15" class="form-control input input-large" placeholder="Message to email users">Dear <firstName>,

For the <?php print SITE_NAME . ' ' . SITE_YEAR; ?> you need to produce a <wordCount>-word maximum summary of your PhD. This summary will be published online, along with all other current CDT students in an online catalogue. It is hoped that highlights from this catalogue will be included in a published leaflet advertising the centre's work.

<strong>IMPORTANT: This process requires you to make your submission by the <deadline>!</strong>

The submission system can be found at <a href="<?php print URI_ROOT; ?>/login" target="_blank"><?php print URI_ROOT; ?>/login</a> and you need to use the following details to login:
<strong>Username:</strong> <username>
<strong>Password:</strong> <password>

You can make as many submissions as you like up to the deadline, only the newest submission will be used. Previous years work can be accessed via the system homepage.

Many thanks and good luck!</textarea>
									</div>
									<hr>
									<div class="form-group">
										<label for="title">Users to email</label>
										<p>Add <span id="cohortLinks"></span> cohorts.</p>
										<textarea name="usernames" id="usernames" rows="15" class="form-control input input-large" placeholder="Enter each university username, seperated by a new line"></textarea>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!-- END EMAIL STAGE -->

					</form>
					<!-- END EDITOR STAGE -->

				</div>
				<nav class="navbar navbar-default navbar-fixed-bottom stage stage-email collapse">
					<div class="navbar navbar-right">
						<div class="btn-group">
							<button class="btn navbar-btn btn-danger" type="button" id="logout">Logout</button>
							<button class="btn navbar-btn btn-success" type="button" id="submit">Send &raquo;</button>
						</div>
					</div>
				</div>
<?php

$cTemplate->set('header', true);
$cTemplate->set('body', $cTemplate->endCapture());

$cTemplate->add('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add('javascript', URI_WEB . '/js/email' . EXT_JS);

print $cTemplate->load('2015');
