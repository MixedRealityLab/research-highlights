<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$rh = \CDT\RH::i();
$oPageTemplate = $rh->cdt_page_template;

$oPageTemplate->startCapture ();

?>
	<div class="container main">
		<div class="row row-offcanvas row-offcanvas-left">
			<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
				<p class="visible-xs">
					<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
				</p>

				<div class="well sidebar-nav">
					<p class="view-by">
						List submissions by
						<br>
						<a href="#" class="list-mode" data-listmode="cohort">Cohort</a> &bull; <a href="#" class="list-mode" data-listmode="name">Name</a> &bull; <a href="#" class="list-mode selected" data-listmode="keyword">Keyword</a> 
					</p>

					<ul class="nav" id="view-list">
					</ul>
				</div>

				<div class="collapse">
					<div class="panel panel-info">
						<div class="panel-heading">About the Centre</div>
						<div class="panel-body">
							<p>The Horizon Centre for Doctoral Training is based at The University of Nottingham and is supported by a £6 million investment from...</p>
							<p><a class="btn btn-default" href="http://www.horizon.ac.uk/About-the-CDT" role="button">Learn More &raquo;</a></p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-9">
				<p class="pull-right visible-xs">
					<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">View Submissions</button>
				</p>

				<div class="jumbotron primary collapse">
					<div class="container">
						<h2>Research Highlights 2015</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium enim pellentesque, pretium tellus sed, bibendum purus. Donec gravida tellus dui, nec placerat quam luctus non. Mauris a arcu at sapien imperdiet fermentum sit amet at dui. Vestibulum eu facilisis tortor. In lectus eros, mollis nec fringilla ut, pharetra non metus. Sed sed purus velit. Mauris malesuada ante ut felis pretium, sit amet volutpat felis iaculis.</p>
						<p>To discover the work being undertaken by PhD students in the centre, use the menu to the left.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php

$oPageTemplate->set ('header', true);
$oPageTemplate->set ('body', $oPageTemplate->endCapture ());

$oPageTemplate->add ('css', URI_WEB . '/css/index' . EXT_CSS);

$oPageTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$oPageTemplate->add ('javascript', URI_WEB . '/js/index' . EXT_JS);

print $oPageTemplate->load ('2015');
