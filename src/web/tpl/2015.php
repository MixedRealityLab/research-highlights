<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
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
		<title><?php print $title ? $title : TITLE; ?></title>
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
		<link rel="apple-touch-icon" sizes="57x57" href="<?php print URI_WEB; ?>/img/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php print URI_WEB; ?>/img/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php print URI_WEB; ?>/img/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php print URI_WEB; ?>/img/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php print URI_WEB; ?>/img/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php print URI_WEB; ?>/img/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php print URI_WEB; ?>/img/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php print URI_WEB; ?>/img/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php print URI_WEB; ?>/img/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="<?php print URI_WEB; ?>/img/favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php print URI_WEB; ?>/img/favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="<?php print URI_WEB; ?>/img/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php print URI_WEB; ?>/img/favicon-16x16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="<?php print URI_WEB; ?>/img/favicon-32x32.png" sizes="32x32">
		<meta name="msapplication-TileColor" content="#2660a9">
		<meta name="msapplication-TileImage" content="<?php print URI_WEB; ?>/img/mstile-144x144.png">
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
				<p>&copy; The University of Nottingham <?php print SITE_YEAR; ?> &bull; Website built and <a href="https://github.com/mporcheron/ResearchHighlights" title="Research Highlights project on GitHub">open sourced</a> by <a href="https://www.porcheron.uk/" title="Martin Porcheron's website">Martin Porcheron</a> &bull; <a href="<?php print URI_HOME; ?>/login" title="<?php print SITE_NAME; ?> Submission Management">Manage your Submission</a></p>
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
		<?php if ($footer): ?>
			<?php print $footer; ?>
		<?php endif; ?>
	</body>
</html>
