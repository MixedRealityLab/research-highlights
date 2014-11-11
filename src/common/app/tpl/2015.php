<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Research Highlights | Horizon CDT (by Martin Porcheron)</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="<?php print URI_HOME; ?>/">
		<link rel="stylesheet" href="sys/css/bootstrap.css">
		<link rel="stylesheet" href="app/css/main.css">
		<?php if ($css): ?>
			<?php foreach ($css as $file): ?>
			<link rel="stylesheet" href="<?php print $file; ?>">
			<?php endforeach; ?>
		<?php endif; ?>
		<script src="sys/js/modernizr-2.6.2-respond-1.1.0.js"></script>
	</head>
	<body>
		<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		
		<?php if ($header): ?>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-left">
					<a class="navbar-brand" href="<?php print URI_HOME; ?>">Horizon CDT &raquo; Research Highlights</a>
				</div>
				<?php if ($nav): ?>
					<?php print $nav; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($body): ?>
			<?php print $body; ?>
		<?php endif; ?>

		<div class="footer collapse">
			<hr>

			<footer>
			<p>&copy; Martin Porcheron <?php print date ('Y'); ?>.</p>
			</footer>
		</div>
		</div>

		<script src="sys/js/jquery-1.11.1.js"></script>
		<script src="sys/js/bootstrap.js"></script>
		<script src="sys/js/bootstrap-tagsinput.js"></script>
		<script src="sys/js/jquery-ui.js"></script>
		<script src="sys/js/jquery.scrollTo.js"></script>
		<script src="sys/js/jquery.autosize.js"></script>

		<?php if ($javascript): ?>
		<?php foreach ($javascript as $file): ?>
			<script src="<?php print $file; ?>"></script>
		<?php endforeach; ?>
		<?php endif; ?>
	</body>
</html>
