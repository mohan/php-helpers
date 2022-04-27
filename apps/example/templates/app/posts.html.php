<?= h1("List of all Posts") ?>

<div class='panel'>
	<?= h3($title) ?>
	<?= p($id ? "Post #$id" : "") ?>
	<?= p($body) ?>
</div>
