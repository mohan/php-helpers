<div id='sidebar'>
	<h2>PHP Helpers</h2>
	<ul>
		<li><?= linkto('root', [], 'Home') ?></li>
		<li><?= linkto('/docs', [], 'Readme') ?></li>
		<li><?= linkto('/docs/specification', [], 'Specification') ?></li>
		<li><?= linkto('/docs/docs', [], 'Docs') ?></li>
		<li><?= linkto('/docs/database-layer', [], 'Database Layer') ?></li>
		<li><?= linkto('/docs/markdown', ['raw'=>true], 'Markdown') ?></li>
		<li><?= linkto('/docs/colors', [], 'Colors') ?></li>
		<li><?= linkto('/docs/notes', [], 'Notes') ?></li>
		<li><?= linkto('/docs/php', [], 'PHP') ?></li>
		<li><?= linkto('/docs/php-helpers-catalyst-book', [], 'Book') ?></li>
		<li><?= linkto('/docs/project-management', [], 'Project Management Tips') ?></li>
	</ul>

	<h3>Sourcecode</h3>
	<ul>
		<li><?= linkto('src', ['src_path'=>'helpers.php'], 'helpers.php') ?></li>
		<li><?= linkto('src', ['src_path'=>'helpers-extra.php'], 'helpers-extra.php') ?></li>
		<li><?= linkto('src', ['src_path'=>'test-helpers.php'], 'test-helpers.php') ?></li>
		<li><?= linkto('src', ['src_path'=>'utils.php'], 'utils.php') ?></li>
	</ul>
	<h3>Partials</h3>
	<ul>
		<li><?= linkto('src', ['src_path'=>'partials/layout-404.html.php'], 'layout-404.html.php') ?></li>
		<li><?= linkto('src', ['src_path'=>'partials/layout-blank.html.php'], 'layout-blank.html.php') ?></li>
		<li><?= linkto('src', ['src_path'=>'partials/layout-navbar.html.php'], 'layout-navbar.html.php') ?></li>
		<li><?= linkto('src', ['src_path'=>'partials/layout-sidebar.html.php'], 'layout-sidebar.html.php') ?></li>
		<li><?= linkto('src', ['src_path'=>'partials/_debugpanel.html.php'], '_debugpanel.html.php') ?></li>
	</ul>
</div>
