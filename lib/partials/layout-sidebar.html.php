<?php
	$layout_options = _arr_get(
						isset($_REQUEST['LAYOUT_OPTIONS']) ? $_REQUEST['LAYOUT_OPTIONS'] : [],
						[
							'HEAD'			=>	false,
							'BODY_CLASS'	=>	''
						]
					);



	
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title><?= _pagetitle(isset($_pagetitle) ? $_pagetitle : '') ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('assets/style.css'); ?>">
	<?php if($layout_options['HEAD']) render_partial('/partials/head.html.php', $args); ?>
</head>
<body id='<?= "action-" . $_REQUEST['ACTION_ID'] ?>' class='layout-sidebar <?= $layout_options['BODY_CLASS'] ?>'>
	<?php require '_debugpanel.html.php'; ?>

	<?= render_partial('/partials/sidebar.html.php', $args); ?>

	<div id='main'>
		<?= isset($_REQUEST['flash']) ? tag($_REQUEST['flash'], ['class'=>'panel text-center']) : '' ?>
		<?php render_partial($template_path, $args); ?>
	</div>

	<?php render_partial('/partials/footer.html.php', $args); ?>
</body>
</html>
