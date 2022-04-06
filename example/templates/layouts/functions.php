<?php function _page_title($_pagetitle)
{
	switch($_REQUEST['CURRENT_ACTION']){
		case 'root': return 'Example';
		case 'docs': return 'Docs - Example';
		case 'docs_view': return "Docs - #{$_GET['path']} - Example";
		default: return isset($_pagetitle) ? "$_pagetitle - Example" : 'Example';
	}
}
?>
<?php function _render_navbar(){ ?>
	<div id='navbar'>
		<h2>Example Application</h2>
		<ul>
			<li><?= linkto('root', 'Home') ?></li>
			<li><?= linkto('/docs', 'Docs') ?></li>
			<li><?= linkto('posts', 'Posts') ?></li>
			<li><?= linkto('new-post', 'New Post') ?></li>
			<li><?= linkto('/post/1', 'Post 1') ?></li>
			<li><?= linkto('404', '404') ?></li>
		</ul>
	</div>
<?php } ?>
<?php function _render_sidebar(){ ?>
	<div id='sidebar'>
		<h2>PHP Helpers</h2>
		<ul>
			<li><?= linkto('root', 'Home') ?></li>
			<li>
				<?= linkto('/docs', 'Docs') ?>
				<ul>
					<li><?= linkto('/docs/helpers', 'Helpers') ?></li>
					<li><?= linkto('/docs/markdown', 'Markdown', ['raw'=>true]) ?></li>
				</ul>
			</li>
		</ul>
	</div>
<?php } ?>
