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
