<html>
<head>
	<title>Example</title>
	<style type="text/css">
		body{
			background: #f0f0f0;
			padding: 0;
			margin: 0;
			font-family: "courier new";
			font-size: 1em;
			line-height: 1.8em;
		}
		ul#nav{
			background: #ccc;
			list-style: none;
			padding: 0;
			margin: 0;
			text-align: center;
		}
		ul#nav li{
			display: inline-block;
			margin: 20px 5px;
		}
		ul#nav li a{
			display: inline-block;
			background: #bbb;
			padding: 5px 15px;
			color: #0000FF;
		}

		#main{
			margin: 100px 200px 0 200px;
			min-height: 60vh;
		}

		.markdown p{ margin: 0 0 3px 0; }
		.markdown .markdown-br{ padding: 7px; }
		.markdown .markdown-italic{ font-style: italic; }
		.markdown .markdown-bold{ font-weight: bold; }
		.markdown .markdown-bold-italic{ font-weight: bold; font-style: italic; }
		.markdown .markdown-code{ display: inline-block; background: #ddd; padding:0 7px; border-radius: 4px; font-size: 0.85em; }
		.markdown .markdown-codeblock{ background: #ddd; padding:25px 25px 20px 25px; border-radius: 4px; }
		.markdown h1{ font-size: 1.3em; margin: 0 0 2px 0; }
		.markdown h2{ font-size: 1.2em; margin: 0 0 2px 0; }
		.markdown h3{ font-size: 1.1em; margin: 0 0 2px 0; }
		.markdown h4{ font-size: 1.0em; margin: 0 0 2px 0; }
		.markdown h5{ font-size: 0.9em; margin: 0 0 2px 0; }

		input[type='text'], textarea{
			width: 100%;
			font-size: 1em;
			padding: 5px;
		}
	</style>
</head>
<body>
	<?php render_partial('../../debugpanel.html.php'); ?>

	<ul id='nav'>
		<li><?= linkto('', 'Home') ?></li>
		<li><?= linkto('posts', 'Posts') ?></li>
		<li><?= linkto('new-post', 'New Post') ?></li>
		<li><?= linkto('post', 'Post 1', ['id'=>1]) ?></li>
		<li><?= linkto('404', '404') ?></li>
	</ul>

	<div id="main">
		<h1>PHP Helpers Example Application</h1>
		<?= tag($_REQUEST['flash']) ?>
		<?php render_partial($template_name, $args); ?>
	</div>
</body>
</html>
