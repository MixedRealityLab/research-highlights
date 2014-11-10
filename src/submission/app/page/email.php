<?php

$oTemplate = \CDT\Submission::template();
$oTemplate->startCapture ();

?>
            <div class="stage stage-email collapse navbar-right">
              <div class="btn-group">
                <button class="btn navbar-btn btn-danger" type="button" id="logout">Logout</button>
                <button class="btn navbar-btn btn-success" type="button" id="submit">Send &raquo;</button>
              </div>
            </div>
<?php

$oTemplate->set ('nav', $oTemplate->endCapture ());
$oTemplate->startCapture ();   

?>
      <div class="container">
        <div class="visible-xs visible-sm">
          <div class="alert alert-danger">Your screen is small and this will make editing your input difficult. It is recommended you use a desktop/laptop for the submission system.</div>
        </div>

        <div class="row main">

          <!-- BEGIN LOGIN STAGE -->
          <form class="stage stage-login collapse">
            <div class="col-sm-6 col-md-6 col-lg-4">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Authentication</h3>
                </div>
                <div class="panel-body">
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                  </div>
                  <br>
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                  </div>
                  <br>
                  <div class="btn-group">
                    <button class="btn btn-primary" type="submit" id="verify">Login &raquo;</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-8">
                <h2>Email User Details</h2>
                <p>Only system administrators can send out emails to users. Please enter a system administrator username and password.</p>
            </div>
          </form>
          <!-- END LOGIN STAGE -->

          <!-- BEGIN EMAIL STAGE -->
          <form class="stage stage-email collapse">
            <div class="col-sm-12 col-md-12 col-lg-12">
              <p>You can use any of the following codes, these are substituted per user at email time: <code>&lt;word-count&gt;</code>, <code>&lt;address&gt;</code>, <code>&lt;username&gt;</code>, <code>&lt;password&gt;</code>, <code>&lt;cohort&gt;</code>,  <code>&lt;first-name&gt;</code>,  <code>&lt;name&gt;</code>.</p>
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
                    <input type="text" class="form-control input input-large" autocomplete="off" name="subject" id="subject" placeholder="Subject for the email" value="[IMPORTANT] Horizon CDT Research Highlights: Login Details for <name>">
                  </div>
                  <hr>
                  <div class="form-group">
                    <label for="title">Message</label>
                    <textarea name="message" id="message" rows="15" class="form-control input input-large" placeholder="Message to email users">Dear <first-name>,

For the Horizon CDT Research Highlights 2015, you need to produce a <word-count>-word maximum summary of your PhD. This summary will be published online, along with all other current CDT students in an online catalogue. It is hoped that highlights from this catalogue will be included in a published leaflet advertising the centre's work.

<strong>IMPORTANT: This process requires you to make your submission by the 31st January 2015!</strong>

 As with previous years, submission takes place via an online system. We have improved this system based on last years feedback, with a focus on making it easier and quicker for you to make a submission.

The submission system can be found at <a href="<?php print URI_HOME; ?>" target="_blank"><?php print URI_HOME; ?></a> and you need to use the following details to login:
<strong>Username:</strong> <username>
<strong>Password:</strong> <password>

You can make as many submissions as you like up to the deadline, only the newest submission will be used. Previous years work can be accessed via the system homepage.

Many thanks and good luck,
Martin Porcheron</textarea>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label for="title">Users to email</label>
                    <p>Add <a href="#" class="addUsers">2014</a>, <a href="#" class="addUsers">2013</a>, <a href="#" class="addUsers">2012</a>, and <a href="#" class="addUsers">2011</a> cohorts.</p>
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
<?php

$oTemplate->set ('header', true);
$oTemplate->set ('body', $oTemplate->endCapture ());

$oTemplate->add ('javascript', 'app/js/main.js');
$oTemplate->add ('javascript', 'app/js/email.js');

print $oTemplate->load ('2015');
