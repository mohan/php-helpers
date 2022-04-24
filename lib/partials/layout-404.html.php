<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>404</title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('assets/style.css'); ?>">
</head>
<body>
	<?php require '_debugpanel.html.php'; ?>
	
	<div id='main' style='min-height:auto;padding:30px 50px;'>
		<?= tag('Error 404! Page not found!', [], 'h1') ?>
		<?= tag('Error URI: ' . $_SERVER['REQUEST_URI'], [], 'p') ?>
		<?= tag($message, ['class'=>'fg-7'], 'p') ?>
	</div>
</body>
</html>
