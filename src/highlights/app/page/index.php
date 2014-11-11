<?php

$oTemplate = \CDT\Submission::template();
$oTemplate->startCapture ();

?>
	<div class="jumbotron primary">
		<div class="container">
			<h1>Research Highlights 2015</h1>
			<p>This website documents the research that is currently being undertaken by current PhD students in the Horizon Centre for Doctoral Training for cohorts that started between 2011 and 2014.</p>
		</div>
	</div>
	<div class="container">
		<hr>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-4 right-line collapse">
				<form>
					<div class="input-group">
						<input type="search" class="form-control" name="k" id="search" placeholder="Search highlights&#8230;">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Search</button>
						</span>
					</div>
				</form>
				<hr>
				<h3>Find highlights&#8230;</h3>
				<ul class="nav nav-pills nav-stacked" role="tablist">
					<li><a href="#">&#8230;	by cohort or student</a></li>
					<li><a href="#">&#8230;	by keywords</a></li>
					<li><a href="#">&#8230;	by title</a></li>
				</ul>
			</div>
			<div class="col-sm-12 spacer hidden-md hidden-lg collapse"></div>
			<div class="col-lg-8 col-md-8 col-sm-12 left-line main collapse">
				<h2>About the Centre</h2>
				<p>The Horizon Centre for Doctoral Training (CDT) at The University of Nottingham was launched in September 2009 and is funded by EPSRC (Grants EP/G037574/1 and L015463/1).</p>
				<p>The Centre is directed by Professor Steve Benford, the Training Programme Manager is Professor Sarah Sharples, and the Centre is managed by Emma Juggins.</p>
				<p>The four year PhD programme focuses on cohort training to equip PhD students for careers in industry, academia or research. The programme includes a taught element and a three month industrial internship.</p>
				<hr>
				<small>The University of Nottingham has made every effort to ensure that the information on this website was accurate when published (May 2015). Please note, however, that the nature of the content means that it is subject to change from time to time and you should therefore consider the information to be guiding rather than definitive.</small>
			</div>
		</div>
	</div>
<?php

$oTemplate->set ('header', true);
$oTemplate->set ('body', $oTemplate->endCapture ());

$oTemplate->add ('css', 'app/css/index.css');

$oTemplate->add ('javascript', 'sys/js/jquery.ba-hashchange.js');
$oTemplate->add ('javascript', 'app/js/main.js');
$oTemplate->add ('javascript', 'app/js/index.js');

print $oTemplate->load ('2015');