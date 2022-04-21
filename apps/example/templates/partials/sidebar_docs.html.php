<div id='sidebar'>
	<h2>PHP Helpers</h2>
	<ul>
		<li><?= linkto('root', 'Home') ?></li>
		<li><?= linkto('/docs', 'Readme') ?></li>
		<li><?= linkto('/docs/specification', 'Specification') ?></li>
		<li><?= linkto('/docs/docs', 'Docs') ?></li>
		<li><?= linkto('/docs/database-layer', 'Database Layer') ?></li>
		<li><?= linkto('/docs/markdown', 'Markdown', ['raw'=>true]) ?></li>
		<li><?= linkto('/docs/colors', 'Colors') ?></li>
		<li><?= linkto('/docs/notes', 'Notes') ?></li>
	</ul>

	<h3>Sourcecode</h3>
	<ul>
		<li><?= linkto('src', 'helpers.php', ['src_path'=>'helpers.php']) ?></li>
		<li><?= linkto('src', 'helpers-extra.php', ['src_path'=>'helpers-extra.php']) ?></li>
		<li><?= linkto('src', 'test-helpers.php', ['src_path'=>'test-helpers.php']) ?></li>
		<li><?= linkto('src', 'utils.php', ['src_path'=>'utils.php']) ?></li>
		<li><?= linkto('src', 'layout-404.html.php', ['src_path'=>'partials/layout-404.html.php']) ?></li>
		<li><?= linkto('src', 'layout-blank.html.php', ['src_path'=>'partials/layout-blank.html.php']) ?></li>
		<li><?= linkto('src', 'layout-navbar.html.php', ['src_path'=>'partials/layout-navbar.html.php']) ?></li>
		<li><?= linkto('src', 'layout-sidebar.html.php', ['src_path'=>'partials/layout-sidebar.html.php']) ?></li>
		<li><?= linkto('src', '_debugpanel.html.php', ['src_path'=>'partials/_debugpanel.html.php']) ?></li>
	</ul>
</div>
