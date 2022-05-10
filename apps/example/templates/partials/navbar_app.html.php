<div id='navbar'>
	<h2>Example Application</h2>
	<ul>
		<li><?= linkto('root', [], 'Home') ?></li>
		<li><?= linkto('/docs', [], 'Docs') ?></li>
		<li><?= linkto('posts', [], 'Posts') ?></li>
		<li><?= linkto('new-post', [], 'New Post') ?></li>
		<li><?= linkto('search', [], 'Search') ?></li>
		<li><?= linkto('/post/1', [], 'Post 1') ?></li>
		<li><?= linkto('404', [], '404') ?></li>
	</ul>
</div>
