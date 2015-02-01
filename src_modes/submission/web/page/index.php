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
			<div class="alert alert-danger"><div class="container"><div class="row">Your screen is small and this will make editing your input difficult. It is recommended you use a desktop/laptop for the submission system.</div></div></div>
		</div>

		<div class="jumbotron primary collapse">
			<div class="container">
				<h1>Hello Horizonauts!</h1>
				<p>Every year, we publish research highlights from each and every student who is currently undertaking research through the CDT. We've made it easier than ever before for you to make your contribution.</p>
				<p><a id="submit" class="btn btn-primary btn-lg" role="button">Let's get started &raquo;</a></p>
			</div>
		</div>
		
		<div class="container main">
			<div class="row">
				<div class="col-md-8 collapse">
				<p>This year, all students in the Centre for Doctoral Training must produce an abstract of their PhD, plus a 140-character &lsquo;tweet&rsquo; summarising their PhD. At the CDT Annual Retreat, we will launch an online catalogue of all submissions. Horizon will also publish each and every &lsquo;PhD in a Tweet&rsquo; in a small leaflet, with a link to your online submission. Furthermore, a select few submissions will be published in a &lsquo;Selected Research Highlights&rsquo; leaflet, including pictures and short descriptions.</p>
				<p>In previous years the highlights have been published as a continuous brochure, however this has changed from this year going forward and will no longer happen. Take a look at previous submissions:</p>
				<ul><li><a href="fil/2014.pdf" target="_blank">2014 Writing Retreat Brochure</li><li><a href="fil/2013.pdf" target="_blank">2013 Writing Retreat Brochure</a></li></ul>
			</div>

			<div class="col-md-4 collapse">
				<div class="panel panel-warning">
					<div class="panel-heading">Need Some Help?</div>
					<div class="panel-body">
						<p>If you find an issue with the submission system, please send a quick email, including what browser/operating system you are using and what problem you have.</p>
						<p><a class="btn btn-default" href="mailto:<?php print EMAIL; ?>" role="button">Send Email &raquo;</a></p>
					</div>
				</div>
			</div>
		</div>
<?php

$cTemplate->set ('header', true);
$cTemplate->set ('body', $cTemplate->endCapture ());

$cTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add ('javascript', URI_WEB . '/js/index' . EXT_JS);

print $cTemplate->load ('2015');
