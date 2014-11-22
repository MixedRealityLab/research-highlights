<?php

$oTemplate = \CDT\RH::i()->cdt_template;
$oTemplate->startCapture ();

?>
            <div class="stage stage-editor navbar-right collapse">
              <div class="btn-group">
                <button class="btn navbar-btn btn-danger" type="button" id="logout">Logout</button>
                <button class="btn navbar-btn  btn-success" type="button" id="submit">Submit &raquo;</button>
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
                    <input type="text" class="form-control" name="username" id="username" placeholder="psxabc">
                  </div>
                  <br>
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="">
                  </div>
                  <br>
                  <div class="btn-group">
                    <button class="btn btn-primary" type="submit" id="verify">Login &raquo;</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-8">
                <h2>Submission Form</h2>
                <p>Firstly, we need to identify who you are. We emailed you the details that you need to login to the system (the email was from martin&#64;porcheron.uk). If you have lost this email, either send an email to <a href="mailto:cdt-rh@porcheron.uk">Martin Porcheron or Kyle Arch</a> and we'll send out a password reminder.</p>
                <p><strong>Hint:</strong> Your username is your university username (e.g. <em>psxmp9</em>) and your password is a long sequence of letters and numbers.</p>
            </div>
          </form>
          <!-- END LOGIN STAGE -->

          <!-- BEGIN EDITOR STAGE -->
          <form class="stage stage-editor collapse">

            <!-- BEGIN TABS -->
            <div class="col-sm-12 col-md-12 col-lg-12">
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#summary" role="tab" data-toggle="tab">Summary</a></li>
                <li role="presentation"><a href="#content" role="tab" data-toggle="tab">Abstract</a></li>
                <li role="presentation"><a href="#about" role="tab" data-toggle="tab">Personal</a></li>
              </ul>
            </div>
            <!-- END TABS -->

            <div class="tab-content">

              <!-- BEGIN SUMMARY TAB -->
              <div role="tabpanel" class="tab-pane fade in active" id="summary">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <p>Please provide a useful summary of your PhD as this information will be indexed and used for the search functionality on the Research Highlights website.</p>
                  <hr>
                </div>

                <div class="container">
                  <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                      <div class="form-group">
                        <label for="title">PhD Title</label>
                        <p class="small">Your title should succinctly define your PhD research.</p>
                        <textarea name="title" id="title" rows="3" class="form-control input input-large" placeholder="An Integrated Approach to Unqiuotous Human-Mouse Enviornmental Interaction"></textarea>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                      <div class="form-group">
                        <label for="tweet">PhD in a Tweet</label>
                        <p class="small">Summarise your PhD in 140 characters or less (<strong class="tweet-rem">140</strong> characters remaining).</p>
                        <textarea name="tweet" id="tweet" rows="3" class="form-control input input-large" placeholder="Conducting an ethnomethodologically informed ethnography on uniquitious Human-Moose interaction within a structured environment."></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="container">
                  <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                      <hr>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                      <hr>
                   </div>
                 </div>
                </div>

                <div class="container">
                  <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                      <div class="form-group">
                        <label for="keywords">Research Keywords</label>
                        <p class="small">Please provide at least <strong>five</strong> keywords that define your work. You should separate each keyword with a comma.</p>
                        <input type="text" class="form-control input input-large" data-role="tagsinput" autocomplete="off" name="keywords" id="keywords" placeholder="">
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                      <div class="form-group">
                        <label for="industryName">External Funder Name</label>
                        <input type="text" class="form-control input input-large" name="industryName" autocomplete="off" id="industryName" placeholder="Industry Partner Ltd.">
                        <br>
                        <label for="industryUrl">External Funder Website</label>
                        <input type="url" class="form-control input input-large" name="industryUrl" autocomplete="off" id="industryUrl" placeholder="http://">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END SUMMARY TAB -->

              <!-- BEGIN SUBMISSION TAB -->
              <div role="tabpanel" class="tab-pane fade" id="content">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <p><strong>Formatting</strong>: You can use the <a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Markdown</a> syntax to format your submission. Markdown syntax is excluded from your word count, but please try to adhere to the <strong class="wordlimit">***</strong> word limit.</p>
                  
                  <p><strong>Referencing</strong>: Please use the numerical referencing system. In your abstract, your citation should be a number surrounded by square brackets (e.g. <em>[3]</em>). Place the full reference in the separate text box at the bottom of this page using an ordered list in markdown syntax. If you don't do this, you may be asked to do it later. Your references are excluded from the word count. If you don't want to include any references, leave the references textbox empty.</p>
                  
                  <p><strong>Images</strong>: You can include images in your submission and when you submit your document, a copy of these images will be automatically made. You should consider hosting your images on your University website address (you may also use a free online service such as <a href="http://imgur.com/" target="_blank">Imgur</a>). Use uncompressed or high-quality images where possible. Please note: <strong>you must</strong> own the copyright to the images, or have rights to redistribute or publish the image publicly.</p>
                  <hr>
                  <p>You should only include content in your submission that is publishable at the time of submission.</p>
                  <hr>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="text">Abstract</label>
                    <textarea name="text" id="text" rows="25" class="form-control input input-large"></textarea>
                    <p class="small">You have <strong class="text-rem">0</strong> words remaining.</p>
                  </div>
                  <div class="form-group">
                    <label for="references">References</label>
                    <textarea name="references" id="references" rows="25" class="form-control input input-large" placeholder="1. Smith, J.P. Studying certainty. Science and Culture 9 (1989) 442."></textarea>
                  </div>
                </div>
                
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title preview-title">Preview</h3>
                    </div>
                    <div class="panel-body">
                      <div class="preview-input"></div>
                      <hr>
                      <div class="preview-supported"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END SUBMISSIOB TAB -->

              <!-- BEGIN ABOUT TAB -->
              <div role="tabpanel" class="tab-pane fade" id="about">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <p>These details have been taken from the University system. If your University email address is different to the one below, please <a href="mailto:cdt-rh@porcheron.uk">send me an email</a>. Details included here will be printed alongside your submission - do not include your website or twitter account if you do not wish for these to be published.</p>
                  <hr>
                </div>
  
                <input type="hidden" name="username" id="submit-user">
                <input type="hidden" name="password" id="submit-pass">
  
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" readonly="true" class="form-control input input-large" name="name" id="name">
                  </div>
                  <hr>
                  <div class="form-group">
                    <label for="cohort">Cohort (starting year)</label>
                    <input type="text" readonly="true" class="form-control input input-large" name="cohort" id="cohort">
                  </div>
                  <hr>
                  <div class="form-group">
                    <label for="email">University Email Address</label>
                    <input type="email" readonly="true" class="form-control input input-large" name="email" id="email">
                  </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="website">Website Address</label>
                    <input type="url" class="form-control input input-large" name="website" id="website" autocomplete="off" value="http://">
                  </div>
                  <hr>
                  <div class="form-group">
                    <label for="twitter">Twitter Username</label>
                    <input type="text" class="form-control" name="twitter" id="twitter" autocomplete="off" placeholder="@SuperHumanMooseReseacher">
                  </div>
                </div>
              </div>
              <!-- END ABOUT TAB -->

            </div>

          </form>
          <!-- END EDITOR STAGE -->
        </div>
<?php

$oTemplate->set ('header', true);
$oTemplate->set ('body', $oTemplate->endCapture ());

$oTemplate->add ('css', 'sys/css/bootstrap-tagsinput.css');
$oTemplate->add ('javascript', 'app/js/main.js');
$oTemplate->add ('javascript', 'app/js/submit.js');

print $oTemplate->load ('2015');
