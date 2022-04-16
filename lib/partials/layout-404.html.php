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
		<h2>Error 404! Page not found!</h2>
		Error URI: <?= tag($_SERVER['REQUEST_URI']) ?>
		<?= tag($message, ['class'=>'text-7']) ?>
	</div>
</body>
</html>
