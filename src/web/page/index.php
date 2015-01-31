<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$oTemplate = I::RH_Template ();

$oTemplate->startCapture ();

?>
	<div class="jumbotron primary collapse">
		<div class="container">
			<h1>Hello, World!</h1>
		</div>
	</div>
<?php

$oTemplate->set ('header', true);
$oTemplate->set ('body', $oTemplate->endCapture ());
print $oTemplate->load ('2015');