<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$cTemplate = I::RH_Template ();

$cTemplate->startCapture ();

?>
	<div class="jumbotron primary collapse">
		<div class="container">
			<h1>Hello, World!</h1>
		</div>
	</div>
<?php

$cTemplate->set ('header', true);
$cTemplate->set ('body', $cTemplate->endCapture ());
print $cTemplate->load ('2015');