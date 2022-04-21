<?php if($raw): ?>
	<h2 class='border-bottom text-3' style='font-size:1em;margin:0;'><?= $_pagetitle ?></h2>
	<pre style="font-size:1.25em;border-left:1px solid #ccc;padding-left:20px;padding-top:0;margin-top:0;">
		<?php highlight_string($text) ?>
	</pre>
<?php else: ?>
	<?= render_markdown($text, [], true); ?>
<?php endif; ?>
