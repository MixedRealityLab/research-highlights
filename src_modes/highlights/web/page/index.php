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
	<div class="container main">
		<div class="row row-offcanvas row-offcanvas-left">
			<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
				<p class="visible-xs">
					<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
				</p>

				<form class="sidebar-form search-form" role="search">
					<input type="search" class="form-control" id="q" placeholder="Search">
				</form>

				<div class="sidebar-nav">

					<p class="view-by">
						List submissions by
						<br>
						<a href="#" class="listMode" data-listmode="cohort">Title</a> &bull; <a href="#" class="listMode" data-listmode="name">Name</a> &bull; <a href="#" class="listMode" data-listmode="keyword">Keyword</a> 
					</p>

					<div class="panel-group nav" id="viewList" role="tablist" aria-multiselectable="true"></div>
				</div>

				<div class="collapse side-footer">
					<div class="panel panel-info">
						<div class="panel-heading">About the Centre</div>
						<div class="panel-body">
							<p>The Horizon Centre for Doctoral Training is based at The University of Nottingham and is supported by a £6 million investment from...</p>
							<p><a class="btn btn-default" href="http://www.horizon.ac.uk/About-the-CDT" role="button">Learn More &raquo;</a></p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-9 printArea">
				<p class="pull-right visible-xs collapse">
					<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">View Submissions</button>
				</p>

				<div class="jumbotron primary">
					<div class="container">
						<h2>Research Highlights <?php print SITE_YEAR; ?></h2>
						<img class="pull-right home-img img-circle" alt="Prof. Steve Benford, Centre Director" src="<?php print URI_WEB; ?>/img/sdb.jpg">
						<p>Welcome to the <?php print SITE_YEAR; ?> edition of the Horizon CDT Research Highlights. Every year, all students within the centre join together to highlight their research to other academics, our industry partners, and the wider public.</p>
						<p>Use the menu to the left of this website to read highlights from this year's edition.</p>
						<p><small>Prof. Steve Benford,<br>Centre Director</small></p>
					</div>
				</div>

				<div class="read">
				</div>
			</div>
		</div>
	</div>
<?php

$oPageTemplate->set ('header', true);
$oPageTemplate->set ('body', $oPageTemplate->endCapture ());

$oPageTemplate->add ('css', URI_WEB . '/css/index' . EXT_CSS);

$oPageTemplate->add ('javascript', URI_SYS . '/js/jquery.ba-hashchange' . EXT_JS);
$oPageTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$oPageTemplate->add ('javascript', URI_WEB . '/js/index' . EXT_JS);

print $oPageTemplate->load ('2015');
