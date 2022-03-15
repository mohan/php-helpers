<?= tag("List of all Posts", [], 'h1') ?>

<div class='panel'>
	<?= tag($title, [], 'h3') ?>
	<?= tag($id ? "Post #$id" : "", [], 'p') ?>
	<?= tag($body, [], 'p') ?>
</div>
