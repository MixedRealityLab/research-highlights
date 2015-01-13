<?php 

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Research Highlights | Horizon CDT</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
		<base href="<?php print URI_HOME; ?>/">
		<link rel="stylesheet" href="<?php print URI_SYS; ?>/css/bootstrap<?php print EXT_CSS; ?>">
		<link rel="stylesheet" href="<?php print URI_WEB; ?>/css/main<?php print EXT_CSS; ?>">
		<?php if ($css): ?>
			<?php foreach ($css as $file): ?>
			<link rel="stylesheet" href="<?php print $file; ?>">
			<?php endforeach; ?>
		<?php endif; ?>
		<script src="<?php print URI_SYS; ?>/js/modernizr-2.6.2-respond-1.1.0<?php print EXT_JS; ?>"></script>
	</head>
	<body>
		<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		
		<?php if ($header): ?>
		<nav class="navbar navbar-fixed-top navbar-inverse">
			<div class="container">
				<div class="navbar-header pull-right">
					<a class="navbar-brand" href="<?php print URI_HOME; ?>"><img src="<?php print URI_WEB; ?>/img/logo.png" alt="Horizon Centre for Doctoral Training" class="logo"></a>
				</div>
				<?php if ($nav): ?>
					<?php print $nav; ?>
				<?php endif; ?>
			</div>
		</nav>
		<img src="<?php print URI_WEB; ?>/img/motif.png" alt="Horizon motif" class="motif">
		<?php endif; ?>

		<?php if ($body): ?>
			<?php print $body; ?>
		<?php endif; ?>

		<div class="container collapse">
			<footer class="row">
				<p>&copy; Martin Porcheron <?php print date ('Y'); ?>. This website is <a href="https://github.com/mporcheron/ResearchHighlights" title="Research Highlights project on GitHub">open source</a>.</p>
			</footer>
		</div>

		<script src="<?php print URI_SYS; ?>/js/jquery-1.11.1<?php print EXT_JS; ?>"></script>
		<script src="<?php print URI_SYS; ?>/js/bootstrap<?php print EXT_JS; ?>"></script>
		<script src="<?php print URI_SYS; ?>/js/bootstrap-tagsinput<?php print EXT_JS; ?>"></script>
		<script src="<?php print URI_SYS; ?>/js/jquery-ui<?php print EXT_JS; ?>"></script>
		<script src="<?php print URI_SYS; ?>/js/jquery.scrollTo<?php print EXT_JS; ?>"></script>
		<script src="<?php print URI_SYS; ?>/js/jquery.autosize<?php print EXT_JS; ?>"></script>

		<?php if ($javascript): ?>
		<?php foreach ($javascript as $file): ?>
			<script src="<?php print $file; ?>"></script>
		<?php endforeach; ?>
		<?php endif; ?>
	</body>
</html>
