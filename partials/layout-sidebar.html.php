<html>
<head>
	<title><?= _page_title(isset($_pagetitle) ? $_pagetitle : '') ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('helpers.css'); ?>">
	<?php if(defined('LAYOUT_RENDER_HEAD')) _render_head() ?>
</head>
<body class='layout-sidebar clear'>
	<?php include('debugpanel.html.php'); ?>

	<?= _render_sidebar(); ?>

	<div id='main'>
		<?= isset($_REQUEST['flash']) ? tag($_REQUEST['flash'], ['class'=>'panel text-center']) : '' ?>
		<?php render_partial($template_name, $args); ?>
	</div>

	<?php if(defined('LAYOUT_RENDER_FOOTER')) _render_footer() ?>
</body>
</html>
