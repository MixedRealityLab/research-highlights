<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// first page argument should be a username
if (isSet ($data[0])) {
	$cUser = I::RH_User ();
	if (!\is_null ($cUser->get ($data[0]))) {
		\define ('READ_USER', $data[0]);
	}
}

$cTemplate = I::RH_Template ();

$cTemplate->startCapture ();

?>
	<div class="centerpage">
		<section>
			<h1>
				My Research in a Tweet:
			</h1>
			<div class="twitter">
				<img src="<?php print URI_WEB; ?>/img/twitter.png" alt="Twitter">
			</div>
			<article>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sodales nec neque ac hendrerit. Nullam porttitor tortor sem, ac sodales purus auctor quis. Nam leo urna, sodales non luctus quis, consectetur ut dui. Phasellus ultrices feugiat pellentesque
				</p>
				<span class="readmore">
					Read more at <a rel="fulltext">http://cdt.horizon.ac.uk/highlights/</a>
				</span>
			</article>
			<div class="tailer">
				<div class="about" rel="author">
					<em>by</em> Alpha Beta
				</div>
				<div class="about">
					2013 cohort
				</div>
				<div class="clear"></div>
			</div>
		</section>
	</div>
<?php

$cTemplate->set ('header', true);
$cTemplate->set ('body', $cTemplate->endCapture ());

$cTemplate->add ('css', URI_WEB . '/css/tweets' . EXT_CSS);

$cTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add ('javascript', URI_WEB . '/js/tweets' . EXT_JS);

print $cTemplate->load ('2015');
