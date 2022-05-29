<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress

_arr_defaults(
	$args,
	[
		'_render_head'	=>	false,
		'_body_class'	=>	''
	]
);

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title><?= _pagetitle(isset($_pagetitle) ? $_pagetitle : '', $args) ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('assets/style.css'); ?>">
	<?php if($args['_render_head']) render_partial("partials/head_" . basename($layout), $args); ?>
</head>
<body id='<?= "action-" . $_REQUEST['ACTION_ID'] ?>' class='layout-blank <?= $args['_body_class'] ?>'>
<?php require '_debugpanel.html.php'; ?>
<?php render_partial($template, $args); ?>
<?php require '_debugpanel.html.php'; ?>
</body>
</html>
