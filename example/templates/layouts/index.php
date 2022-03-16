<html>
<head>
	<title><?= isset($_pagetitle) ? "$_pagetitle - " : '' ?>Example</title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('style.css'); ?>">
</head>
<body>
	<?php render_partial('../../debugpanel.html.php'); ?>

	<ul id='nav'>
		<li><?= linkto('', 'Home') ?></li>
		<li><?= linkto('docs', 'Docs') ?></li>
		<li><?= linkto('posts', 'Posts') ?></li>
		<li><?= linkto('new-post', 'New Post') ?></li>
		<li><?= linkto('post', 'Post 1', ['_p'=>"post/1"]) ?></li>
		<li><?= linkto('404', '404') ?></li>
	</ul>

	<div id="main">
		<?= isset($_REQUEST['flash']) ? tag($_REQUEST['flash'], ['class'=>'panel text-center']) : '' ?>
		<?php render_partial($template_name, $args); ?>
	</div>
</body>
</html>
