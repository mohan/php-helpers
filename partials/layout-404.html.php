<!DOCTYPE html>
<html>
<head>
	<title>404</title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('helpers.css'); ?>">
</head>
<body>
	<?php require('_debugpanel.html.php'); ?>
	
	<div id='main' style='min-height:auto;padding:30px 50px;'>
		<h2>Error 404! Page not found!</h2>
		<?= tag($message, ['class'=>'text-7']) ?>
	</div>
</body>
</html>
