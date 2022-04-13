<!DOCTYPE html>
<html>
<head>
	<title><?= _page_title(isset($_pagetitle) ? $_pagetitle : '') ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('helpers.css'); ?>">
	<?php if(defined('LAYOUT_RENDER_HEAD')) render_partial(APP_NAME . '/_head.html.php', $args); ?>
</head>
<body class='layout-navbar <?= defined('LAYOUT_BODY_CLASS') ? LAYOUT_BODY_CLASS : '' ?>'>
	<?php require('_debugpanel.html.php'); ?>

	<?= render_partial(APP_NAME . '/_navbar.html.php', $args); ?>

	<div id="main">
		<?= isset($_REQUEST['flash']) ? tag($_REQUEST['flash'], ['class'=>'panel text-center']) : '' ?>
		<?php render_partial($template_path, $args); ?>
	</div>

	<?php render_partial(APP_NAME . '/_footer.html.php', $args); ?>
</body>
</html>
