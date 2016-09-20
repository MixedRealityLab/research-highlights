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
                                <div class="card-title">Administration</div>
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

                    <!-- BEGIN ADMIN STAGE -->

                    <!-- BEGIN TABS -->
                    <div class="col-sm-12 col-md-12 col-lg-12 stage stage-admin collapse">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab-students" role="tab" data-toggle="tab">Students</a></li>
                            <li role="presentation"><a href="#tab-admins" role="tab" data-toggle="tab">Adminstrators</a></li>
                            <li role="presentation"><a href="#tab-deadlines" role="tab" data-toggle="tab">Deadlines</a></li>
                            <li role="presentation"><a href="#tab-wordcounts" role="tab" data-toggle="tab">Word Counts</a></li>
                            <li role="presentation"><a href="#tab-funding" role="tab" data-toggle="tab">Funding Statements</a></li>
                            <li role="presentation" class="pull-right"><a href="#tab-email" role="tab" data-toggle="tab">Send Emails</a></li>
                        </ul>
                    </div>
                    <!-- END TABS -->

                    <div class="tab-content stage stage-admin collapse">

                        <!-- BEGIN STUDENTS TAB -->
                        <div role="tabpanel" class="tab-pane fade active in" id="tab-students">
                            <form class="form-students">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <p>Most values are self-explanatory, but a few are more complex. The <strong>Funding Statement</strong> value relates to <em>ID</em> on the <em>Funding Statements</em> tab. <strong>Login Enabled</strong> can be <code>true</code> or <code>false</code> only. <strong>Show Submission</strong> decides whether a user's submission should be shown on the website, and can be <code>true</code> or <code>false</code> only. <strong>Notify</strong> emails administrators when a submission is altered, and can be <code>true</code> or <code>false</code> only. </p>
                                    <hr>
                                    <p><strong>Always create deadlines and funding statements before referencing them in the user table.</strong></p>
                                    <hr>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Manage Students</h3>
                                        </div>
                                        <input type="hidden" name="username" class="submit-user">
                                        <input type="hidden" name="password" class="submit-pass">
                                        <div class="panel-body">
                                            <div id="students"></div>
                                            <br>
                                            <div class="btn-group">
                                                <button class="btn navbar-btn btn-warning btn-deleteRow" type="button" data-deleteTabularRow="deadlines">Delete Last Row</button>
                                            </div>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button class="btn navbar-btn btn-success" type="submit" class="submit">Save Students &raquo;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END STUDENTS TAB -->

                        <!-- BEGIN DEADLINES TAB -->
                        <div role="tabpanel" class="tab-pane fade" id="tab-deadlines">
                            <form class="form-deadlines">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <p>Do not include any punctuation in the numbers. To add a new cohort, simple click inside the final cell and hit the <code>[tab]</code> button on your keyboard.</p>
                                    <hr>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Deadlines by Cohort</h3>
                                        </div>
                                        <input type="hidden" name="username" class="submit-user">
                                        <input type="hidden" name="password" class="submit-pass">
                                        <div class="panel-body">
                                            <div id="deadlines"></div>
                                            <br>
                                            <div class="btn-group">
                                                <button class="btn navbar-btn btn-warning btn-deleteRow" type="button" data-deleteTabularRow="deadlines">Delete Last Row</button>
                                            </div>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button class="btn navbar-btn btn-success" type="submit" class="submit">Save Deadlines &raquo;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END DEADLINES TAB -->

                        <!-- BEGIN WORD COUNTS TAB -->
                        <div role="tabpanel" class="tab-pane fade" id="tab-wordcounts">
                            <form class="form-wordcounts">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <p>Do not include any punctuation in the numbers. To add a new cohort, simple click inside the final cell and hit the <code>[tab]</code> button on your keyboard.</p>
                                    <hr>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Word Counts by Cohort</h3>
                                        </div>
                                        <input type="hidden" name="username" class="submit-user">
                                        <input type="hidden" name="password" class="submit-pass">
                                        <div class="panel-body">
                                            <div id="wordcounts"></div>
                                            <br>
                                            <div class="btn-group">
                                                <button class="btn navbar-btn btn-warning btn-deleteRow" type="button" data-deleteTabularRow="deadlines">Delete Last Row</button>
                                            </div>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button class="btn navbar-btn btn-success" type="submit" class="submit">Save Word Counts &raquo;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END WORD COUNTS TAB -->

                        <!-- BEGIN FUNDING STATEMENTS TAB -->
                        <div role="tabpanel" class="tab-pane fade" id="tab-funding">
                            <form class="form-funding">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <p>Do not include any punctuation in the ID. To add a new funding statement, simple click inside the final cell and hit the <code>[tab]</code> button on your keyboard. You cam individually select which user has which funding statement from the <em>Users</em> tab.</p>
                                    <hr>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Funding Statements</h3>
                                        </div>
                                        <input type="hidden" name="username" class="submit-user">
                                        <input type="hidden" name="password" class="submit-pass">
                                        <div class="panel-body">
                                            <div id="funding"></div>
                                            <br>
                                            <div class="btn-group">
                                                <button class="btn navbar-btn btn-warning btn-deleteRow" type="button" data-deleteTabularRow="deadlines">Delete Last Row</button>
                                            </div>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button class="btn navbar-btn btn-success" type="submit" class="submit">Save Funding Statements &raquo;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END FUNDING STATEMENTS TAB -->

                        <!-- BEGIN ADMINS TAB -->
                        <div role="tabpanel" class="tab-pane fade" id="tab-admins">
                            <form class="form-admins">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <p>Most values are self-explanatory, but a few are more complex. The <strong>Funding Statement</strong> value relates to <em>ID</em> on the <em>Funding Statements</em> tab. <strong>Login Enabled</strong> can be <code>true</code> or <code>false</code> only. <strong>Show Submission</strong> decides whether a user's submission should be shown on the website, and can be <code>true</code> or <code>false</code> only. <strong>Notify</strong> emails administrators when a submission is altered, and can be <code>true</code> or <code>false</code> only. </p>
                                    <hr>
                                    <p><strong>Always create deadlines and funding statements before referencing them in the user table.</strong></p>
                                    <hr>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Manage Administrators</h3>
                                        </div>
                                        <input type="hidden" name="username" class="submit-user">
                                        <input type="hidden" name="password" class="submit-pass">
                                        <div class="panel-body">
                                            <div id="admins"></div>
                                            <br>
                                            <div class="btn-group">
                                                <button class="btn navbar-btn btn-warning btn-deleteRow" type="button" data-deleteTabularRow="deadlines">Delete Last Row</button>
                                            </div>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button class="btn navbar-btn btn-success" type="submit" class="submit">Save Administrators &raquo;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END ADMINS TAB -->

                        <!-- BEGIN EMAIL TAB -->
                        <div role="tabpanel" class="tab-pane fade" id="tab-email">
                            <form class="form-email">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <p>You can use any of the following codes, these are substituted per user at email time: <?php $keys = '';
                                    foreach (\RH\Model\User::substsKeys() as $key) :
                                        $keys .= (empty($keys) ? '' : ', ') . '<code>'. \htmlentities($key) .'</code>';
                                    endforeach;
                                    print $keys; ?>.</p>
                                    <p>Only users whose accounts are enabled will receive emailed.</p>
                                    <hr>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Send Emails</h3>
                                        </div>
                                        <input type="hidden" name="username" class="submit-user">
                                        <input type="hidden" name="password" class="submit-pass">
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
                                            <br>
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button class="btn navbar-btn btn-success" type="submit" class="submit">Send Email(s) &raquo;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END EMAIL TAB -->

                    </div>
                    <!-- END ADMIN STAGE -->

                </div>
                <nav class="navbar navbar-default navbar-fixed-bottom stage stage-admin collapse">
                    <div class="navbar navbar-right">
                        <div class="btn-group">
                            <button class="btn navbar-btn btn-danger" type="button" id="logout">Logout</button>
                        </div>
                    </div>
                </div>
<?php

$cTemplate->set('header', true);
$cTemplate->set('body', $cTemplate->endCapture());

$cTemplate->add('css', URI_SYS . '/css/tabular-input' . EXT_CSS);
$cTemplate->add('css', URI_WEB . '/css/admin' . EXT_CSS);

$cTemplate->add('javascript', URI_SYS . '/js/jquery.ui.widget' . EXT_JS);
$cTemplate->add('javascript', URI_SYS . '/js/tabular-input' . EXT_JS);
$cTemplate->add('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add('javascript', URI_WEB . '/js/admin' . EXT_JS);

print $cTemplate->load('2015');
