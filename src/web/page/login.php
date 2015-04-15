<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$cTemplate = I::RH_Template ();

$cTemplate->startCapture ();

?>
		<div class="visible-xs visible-sm">
			<div class="alert alert-danger"><div class="container"><div class="row">Your screen is small and this will make editing your input difficult. It is recommended you use a desktop/laptop while submitting/modifying solutions.</div></div></div>
		</div>

		<div class="container main">
			<div class="row main">

				<div style="display: none;">
					<input id="fileupload" type="file" name="files[]" data-url="<?php print URI_HOME; ?>/do/upload" multiple>
				</div>

				<!-- BEGIN LOGIN STAGE -->
				<form class="stage stage-login collapse">
					<div class="card-container">
						<div class="card">
							<div class="card-title">Manage your Submission</div>
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
								<input type="text" class="form-control" name="username" id="username" placeholder="University username" spellcheck="false">
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

				<!-- BEGIN EDITOR STAGE -->
				<form class="stage stage-editor collapse">

				<!-- BEGIN TABS -->
				<div class="col-sm-12 col-md-12 col-lg-12">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#summary" role="tab" data-toggle="tab">Summary</a></li>
						<li role="presentation"><a href="#content" role="tab" data-toggle="tab">Research</a></li>
						<li role="presentation"><a href="#about" role="tab" data-toggle="tab">Personal</a></li>
						<li role="presentation" class="pull-right"><a href="#formatting" role="tab" data-toggle="tab">Formatting</a></li>
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
										<textarea name="title" id="title" rows="3" class="form-control input input-large" placeholder="An Integrated Approach to Unqiuotous Human-Mouse Enviornmental Interaction" spellcheck="true" lang="gb"></textarea>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="tweet">PhD in a Tweet</label>
										<p class="small">Summarise your PhD in 140 characters or less (<strong class="tweet-rem">125</strong> characters remaining, <em>15</em> characters are used to link to your submission).</p>
										<textarea name="tweet" id="tweet" rows="3" class="form-control input input-large" placeholder="Conducting an ethnomethodologically informed ethnography on uniquitious Human-Moose interaction within a structured environment." spellcheck="true" lang="gb"></textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="hidden-xs hidden-sm container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
									<hr>
								</div>
								<div class="col-md-6 col-lg-6">
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
										<input type="text" class="form-control input input-large" data-role="tagsinput" autocomplete="off" name="keywords" id="keywords" placeholder="" spellcheck="true" lang="gb">
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="industryName">External Partner Name</label>
										<input type="text" class="form-control input input-large" name="industryName" autocomplete="off" id="industryName" placeholder="Industry Partner Ltd." spellcheck="false">
										<br>
										<label for="industryUrl">External Partner Website</label>
										<input type="url" class="form-control input input-large" name="industryUrl" autocomplete="off" id="industryUrl" placeholder="http://" spellcheck="false">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END SUMMARY TAB -->

					<!-- BEGIN SUBMISSION TAB -->
					<div role="tabpanel" class="tab-pane fade" id="content">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<p>Information and examples on formatting your submission is available on the <em>Formatting</em> tab. <strong>Please note:</strong> you should only include content in your submission that is publishable at the time of submission, and that you have the legal right to publish.</p>
							<hr>
						</div>
						<div class="container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="text">Main Text</label>
										<p>Do not include the title of your PhD here.</p>
										<textarea name="text" id="text" rows="25" class="form-control input input-large" spellcheck="true" lang="gb"></textarea>
										<p class="small">You have <strong class="text-rem">0</strong> words remaining.</p>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-6 preview hidden-xs hidden-sm">
									<div class="preview-text"></div>
								</div>
							</div>
						</div>

						<div class="hidden-xs hidden-sm container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
									<hr>
								</div>
								<div class="col-md-6 col-lg-6">
									<hr>
								 </div>
							</div>
						</div>

						<div class="container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="references">References</label>
										<p>Include your bibliography here. Please insert text as a numbered list in Markdown format (e.g. each line looks like <code>1. Reference Here</code>). Do not leave blank lines between references.</p>
										<textarea name="references" id="references" rows="25" class="form-control input input-large" placeholder="1. Smith, J.P. Studying certainty. Science and Culture 9 (1989) 442." spellcheck="false"></textarea>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-6 preview hidden-xs hidden-sm">
									<div class="preview-references"></div>
								</div>
							</div>
						</div>

						<div class="hidden-xs hidden-sm container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
									<hr>
								</div>
								<div class="col-md-6 col-lg-6">
									<hr>
								 </div>
							</div>
						</div>

						<div class="container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="publications">Publications in the Last Year</label>
										<p>Include links to blog posts, newspaper articles and full citations for conferences papers/journal articles that have been published in the last twelve months for which you have been an author. Please insert these as a numbered list in Markdown format, in the same bibliographical format as above.
										<p>For further formatting information (including links), check out the <em>Formatting</em> tab.</p>
										<textarea name="publications" id="publications" rows="25" class="form-control input input-large"></textarea>
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-6 preview hidden-xs hidden-sm">
									<div class="preview-publications"></div>
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

						<div class="hidden-xs hidden-sm container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
								</div>
								<div class="col-md-6 col-lg-6">
									<small class="preview-fundingStatement"></small>
								</div>
							</div>
						</div>
					</div>
					<!-- END SUBMISSIOB TAB -->

					<!-- BEGIN ABOUT TAB -->
					<div role="tabpanel" class="tab-pane fade" id="about">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<p>These details have been taken from the University system. If your University email address is different to the one below, please <a href="mailto:<?php print EMAIL; ?>">send me an email</a>. Details included here will be printed alongside your submission - do not include your website or twitter account if you do not wish for these to be published.</p>
							<hr>
						</div>

						<input type="hidden" name="saveAs" id="saveAs">
						<input type="hidden" name="username" id="editor-user">
						<input type="hidden" name="password" id="editor-pass">

						<div class="container">
							<div class="row">
								<div class="col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="name">Full Name</label>
										<input type="text" readonly="true" class="form-control input input-large" name="name" id="name">
									</div>
									<div class="hidden-xs hidden-sm">
										<hr>
									</div>
									<div class="form-group">
										<label for="cohort">Cohort (starting year)</label>
										<input type="text" readonly="true" class="form-control input input-large" name="cohort" id="cohort">
									</div>
									<div class="hidden-xs hidden-sm">
										<hr>
									</div>
									<div class="form-group">
										<label for="email">University Email Address</label>
										<input type="email" readonly="true" class="form-control input input-large" name="email" id="email">
									</div>
								</div>

								<div class="col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="website">Website Address</label>
										<input type="url" class="form-control input input-large" name="website" id="website" autocomplete="off" value="http://" spellcheck="false">
									</div>
									<div class="hidden-xs hidden-sm">
										<hr>
									</div>
									<div class="form-group">
										<label for="twitter">Twitter Username</label>
										<input type="text" class="form-control" name="twitter" id="twitter" autocomplete="off" placeholder="@SuperHumanMooseReseacher" spellcheck="false">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END ABOUT TAB -->

					<!-- BEGIN SUBMISSION TAB -->
					<div role="tabpanel" class="tab-pane fade" id="formatting">
						<div class="container">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<p>This page details how to format your text in your submission, with code examples on the left, and what the output looks like on the right. <strong>Top tip:</strong> leave blank lines between text to start a new paragraph, or to change paragraph style (e.g. from text to a bullet-point list)</p>
							</div>

							<hr class="preview-break">

							<h1 id="headers" class="formatting-title">Titles</h1>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-left">
								<pre>To insert a header, use a `#`` at the start of the line. The more `#`s you use, the smaller the header will be.

# A Level 1 Header
Lorem ipsum dolor sit amet, consectetur adipiscing elit.

## A Level 2 Header
Lorem ipsum dolor sit amet, consectetur adipiscing elit.

#### A Level 4 Header
Lorem ipsum dolor sit amet, consectetur adipiscing elit.</pre>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-right preview">
								<p>To insert a header, use a <code>#</code> at the start of the line. The more <code>#</code>s you use, the smaller the header will be.</p>

								<h1>A Level 1 Header</h1>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

								<h2>A Level 2 Header</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

								<h4>A Level 4 Header</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
							</div>

							<hr class="preview-break">

							<h1 id="lists" class="formatting-title">Lists</h1>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-left">
								<pre>There are two types of lists:

1. Ordered (numbered)
2. Unordered (bulleted)

- Ordered lists start with numbers
- You put a new element on each line
- Unordered lists start with `-`s
- And continue in the same manner</pre>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-right">
								<p>There are two types of lists:</p>
								<ol>
									<li>Ordered (numbered)</li>
									<li>Unordered (bulleted)</li>
								</ol>
								<ul>
									<li>Ordered lists start with numbers</li>
									<li>You put a new element on each line</li>
									<li>Unordered lists start with <code>-</code>s</li>
									<li>And continue in the same manner</li>
								</ul>
							</div>

							<hr class="preview-break">

							<h1 id="styles" class="formatting-title">Bold / Italic Text</h1>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-left">
								<p></p>

								<pre>You can make text italic or bold with `*`s.

* One asterisk makes it italic *
** Two asterisks makes it bold **</pre>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-right">
								<p>You can make text italic or bold with <code>*</code>s.</p>

								<p><em>One asterisk makes it italic</em></p>
								<p><strong>Two asterisks makes it bold</strong></p>
							</div>

							<hr class="preview-break">

							<h1 id="links" class="formatting-title">Links</h1>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-left">
								<pre>You can easily create links to other webpages.

This produces a [link](http://www.example.com/) - how cool!</pre>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-right">
								<p>You can easily create links to other webpages.</p>
								<p>This produces a <a href="http://www.example.com/">link</a> - how cool!</p>
							</div>

							<hr class="preview-break">

							<h1 id="quotes" class="formatting-title">Quotes</h1>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-left">
								<pre>Quotes may include other Markdown formatting like bold or italic.

> this is a *quote*</pre>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-right">
								<p>Quotes may include other Markdown formatting like bold or italic.</p>
								<blockquote>this is a <em>quote</em></blockquote>
							</div>

							<hr class="preview-break">

							<h1 id="images" class="formatting-title">Images</h1>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-left">
								<pre>You can include images in your submission. To insert an image, click in the abstract text area where you would like the image to appear and simply drag and drop the image from your computer into the text area.

Images are given a caption in the order they appear in your submission.

![This is my image caption](http://placekitten.com/g/200/300)

Please note: **you must** own the copyright to the images, or have rights to redistribute or publish the image publicly.</pre>
								</div>

							<div class="col-sm-6 col-md-6 col-lg-6 preview-right">
								<p>You can include images in your submission. To insert an image, click in the abstract text area where you would like the image to appear and simply drag and drop the image from your computer into the text area.</p>

								<p>Images are given a caption in the order they appear in your submission.</p>

								<div style="text-align: center">
									<img src="http://placekitten.com/g/200/300" alt="This is my image caption" style="max-width: 100%">
									<p style="text-align: center"><strong>Figure 1: This is my image caption</strong></p>
								</div>

								<p>Please note: <strong>you must</strong> own the copyright to the images, or have rights to redistribute or publish the image publicly.</p>
							</div>

							<hr class="preview-break">

							<h1 id="references" class="formatting-title">References</h1>

							<div class="col-sm-12 col-md-12 col-lg-12">
								<p>Please use the <em>numerical referencing system</em>.</p>

								<p><strong>In your text</strong> you should include your citation as be a number surrounded by square brackets (e.g. <code>[3]</code>). Citations do not count to your word count if you follow this style.</p>

								<p><strong>In the bibliography:</strong> use a markdown-style numbered list (see <em>Lists</em> above). The bibliography does not count to your word count.</p>
							</div>

							<hr class="preview-break">
						</div>
					<!-- END FORMATTING TIPS TAB -->

				</div>

			</form>
			<!-- END EDITOR STAGE -->
		</div>

		<nav class="navbar navbar-default navbar-fixed-bottom stage stage-editor collapse">
			<form class="navbar-form navbar-left hidden" role="user" id="profile-form">
				<input type="hidden" name="username" id="admin-user">
				<input type="hidden" name="password" id="admin-pass">
				<input type="text" class="form-control" id="profile" name="profile" placeholder="View a submission">
			</form>
			<div class="navbar navbar-right">
				<div class="btn-group">
					<button class="btn navbar-btn btn-danger" type="button" id="logout">Logout</button>
					<button class="btn navbar-btn btn-success" type="button" id="submit">Submit &raquo;</button>
				</div>
			</div>
		</nav>

		<div id="progress">
			<div class="bar" style="width: 0%;"></div>
		</div>
<?php

$cTemplate->set ('header', true);
$cTemplate->set ('body', $cTemplate->endCapture ());

$cTemplate->add ('css', URI_SYS . '/css/bootstrap-tagsinput' . EXT_CSS);
$cTemplate->add ('javascript', URI_SYS . '/js/jquery.ui.widget' . EXT_JS);
$cTemplate->add ('javascript', URI_SYS . '/js/jquery.iframe-transport' . EXT_JS);
$cTemplate->add ('javascript', URI_SYS . '/js/jquery.fileupload' . EXT_JS);
$cTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add ('javascript', URI_WEB . '/js/login' . EXT_JS);

print $cTemplate->load ('2015');
