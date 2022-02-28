<html>
<head>
	<title>Example</title>
</head>
<body>
	<ul>
		<li><?= linkto('', 'Home') ?></li>
		<li><?= linkto('posts', 'Posts') ?></li>
		<li><?= linkto('new-post', 'New Post') ?></li>
		<li><?= linkto('post', 'Post 1', ['id'=>1]) ?></li>
		<li><?= linkto('zxcv', '404') ?></li>
	</ul>
	<?= tag($_REQUEST['flash']) ?>
	<?php render_partial($template_name, $args); ?>
</body>
</html>
