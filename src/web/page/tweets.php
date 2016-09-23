<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$cTemplate = I::RH_Template();

$cTemplate->startCapture();

?>
    <div id="dialog" class="centerpage hide collapse">
        <section>
            <h1 id="title">
                My Research in a Tweet:
            </h1>
            <div class="twitter">
                <img src="<?php print URI_WEB; ?>/img/twitter.png" alt="Twitter">
            </div>
            <article>
                <p id="tweet">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sodales nec neque ac hendrerit. Nullam porttitor tortor sem, ac sodales purus auctor quis. Nam leo urna, sodales non luctus quis, consectetur ut dui. Phasellus ultrices feugiat pellentesque
                </p>
                <span class="readmore">
                    Read more at <a rel="fulltext"><?php print URI_HOME; ?>/read/<span id="username">username</span></a>
                </span>
            </article>
            <div class="tailer">
                <div class="about" rel="author">
                    <em>by</em> <span id="author">Alpha Beta</span>
                </div>
                <div class="about">
                    <span id="cohort">2013</span> Cohort
                </div>
                <div class="clear"></div>
            </div>
        </section>
    </div>
<?php

$cTemplate->set('header', true);
$cTemplate->set('body', $cTemplate->endCapture());

$cTemplate->add('css', URI_WEB . '/css/tweets' . EXT_CSS);

$cTemplate->add('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add('javascript', URI_WEB . '/js/tweets' . EXT_JS);

print $cTemplate->load('2015');
