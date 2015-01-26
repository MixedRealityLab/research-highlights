<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$oPageTemplate = I::RH_Page_Template ();

$oPageTemplate->startCapture ();

?>
	<div class="jumbotron primary collapse">
		<div class="container">
			<h1>Hello, World!</h1>
		</div>
	</div>
<?php

$oPageTemplate->set ('header', true);
$oPageTemplate->set ('body', $oPageTemplate->endCapture ());
print $oPageTemplate->load ('2015');