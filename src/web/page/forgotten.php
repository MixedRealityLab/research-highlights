<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$cTemplate = I::RH_Template ();
$cUser = I::RH_User ();

$cTemplate->startCapture ();

?>
			<div class="container main">
				<div class="row main">

					<!-- BEGIN FORGOTTEN STAGE -->
					<form class="stage stage-forgotten collapse">
						<div class="col-sm-6 col-md-6 col-lg-4 input-panel">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h3 class="panel-title">Enter Your Details</h3>
								</div>
								<div class="panel-body">
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
										<input type="text" class="form-control" name="username" id="username" placeholder="e.g. zx12345 or psxab1">
									</div>
									<em>or</em>
									<br>
									<div class="input-group">
										<span class="input-group-addon">@</span>
										<input type="email" class="form-control" name="email" id="email" placeholder="...@nottingham.ac.uk">
									</div>
									<br>
									<div class="btn-group">
										<button class="btn btn-primary" type="submit" id="request">Request Password &raquo;</button>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-8">
							<h2>Retrieve your Password</h2>
							<p>Please enter either your username or your email addresses.</p>
							<p>IDIC students who are now based in Ningbo should use their username beginning with <em>zx...</em>.</p>
						</div>
					</form>
					<!-- END FORGOTTEN STAGE -->

				</div>
<?php

$cTemplate->set ('header', true);
$cTemplate->set ('body', $cTemplate->endCapture ());

$cTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add ('javascript', URI_WEB . '/js/forgotten' . EXT_JS);

print $cTemplate->load ('2015');
