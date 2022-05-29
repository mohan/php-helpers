<div id='navbar'>
	<h2>Example Application</h2>
	<ul>
		<li><?= linkto('root', [], 'Home') ?></li>
		<li><?= linkto('/dashboard', [], 'Dashboard') ?></li>
		<li><?= linkto('404', [], '404') ?></li>
	</ul>
	<ul>
		<li><?= linkto('posts', [], 'Posts') ?></li>
		<li><?= linkto('new-post', [], 'New Post') ?></li>
		<li><?= linkto('/post/1', [], 'Post 1') ?></li>
		<li><?= linkto('search', [], 'Search') ?></li>
	</ul>
	<ul>
		<li><?= linkto('/docs', [], 'Docs') ?></li>
		<li><?= linkto('/docs/php', [], 'PHP Tutorial') ?></li>
		<li><?= linkto('book', [], 'Book') ?></li>
	</ul>
</div>
