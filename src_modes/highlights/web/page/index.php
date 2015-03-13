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
	<div class="container main">
		<div class="row row-offcanvas row-offcanvas-left">
			<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
				<p class="visible-xs">
					<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
				</p>

				<form class="sidebar-form search-form collapse" role="search">
					<input type="search" class="form-control" id="q" placeholder="Search">
				</form>

				<div class="sidebar-nav">

					<p class="view-by collapse">
						List submissions by
						<br>
						<a href="#" class="listMode" data-listmode="cohort">Title</a> &bull; <a href="#" class="listMode" data-listmode="name">Name</a> &bull; <a href="#" class="listMode" data-listmode="keyword">Keyword</a>
					</p>

					<div class="panel-group nav collapse" id="viewList" role="tablist" aria-multiselectable="true"></div>
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
					<button type="button" class="btn btn-primary btn-offcanvas" data-toggle="offcanvas">View Submissions</button>
				</p>

				<div class="jumbotron primary collapse home noAutoFadeIn">
					<div class="container">
						<h2>Research Highlights <?php print SITE_YEAR; ?></h2>
						<img class="pull-right home-img img-circle" alt="Prof. Steve Benford, Centre Director" src="<?php print URI_WEB; ?>/img/sdb.jpg">
						<p>Welcome to the <?php print SITE_YEAR; ?> edition of the Horizon Centre for Doctoral Training (CDT) Research Highlights. Every year, all PhD students within the centre come together to publicise the work, and document their progress over the last twelve months.</p>
						<p>This website highlights the work of over seventy PhD students, from a range of different disciplines, and provides a comprehensive overview of the work undertaken through the CDT.</p>
						<p><small>Prof. Steve Benford,<br>Centre Director</small></p>
					</div>
				</div>

				<div class="featureWall home noAutoFadeIn">
					<h2 class="collapse">Highlighted PhD Topics</h2>
					<br>

					<div class="row">

						<div class="col-md-4 collapse">
							<div class="well feature" style="background-image: url(<?php print URI_HOME; ?>/web/img/home-psxpb2.png)">
								<a href="#read=psxpb2" title="Neighbourhoods: Identifying the Places that People Talk About on the Web">
									<p>
										<span>Mapping UK urban neighbourhoods from postal address data automatically extracted from the internet</span>
										<small>Paul Brindley (2011 Cohort)</small>
									</p>
								</a>
							</div>
						</div>

						<div class="col-md-4 collapse">
							<div class="well feature" style="background-image: url(<?php print URI_HOME; ?>/web/img/home-psxkga.jpg)">
								<a href="#read=psxkga" title="Understanding Teamwork In Online Games">
									<p>
										<span>Is talking to the rest of your team in Call of Duty actually worth it?</span>
										<small>Kyle Arch (2013 Cohort)</small>
									</p>
								</a>
							</div>
						</div>

						<div class="col-md-4 collapse">
							<div class="well feature" style="background-image: url(<?php print URI_HOME; ?>/web/img/home-psxhama.jpg)">
								<a href="#read=psxhama" title="Real-time Mental Workload Feedback using fNIRS">
									<p>
										<span>Real-time Mental Workload Feedback using fNIRs</span>
										<small>Horia Maior (2012 Cohort)</small>
									</p>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="read noAutoFadeIn collapse">
				</div>

				<div class="loading">
					<img src="<?php print URI_WEB; ?>/img/loading.gif" alt="Loading">
				</div>
			</div>
		</div>
	</div>
<?php

$cTemplate->set ('header', true);
$cTemplate->set ('body', $cTemplate->endCapture ());

$cTemplate->add ('css', URI_WEB . '/css/index' . EXT_CSS);

$cTemplate->add ('javascript', URI_SYS . '/js/jquery.ba-hashchange' . EXT_JS);
$cTemplate->add ('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add ('javascript', URI_WEB . '/js/index' . EXT_JS);

print $cTemplate->load ('2015');
