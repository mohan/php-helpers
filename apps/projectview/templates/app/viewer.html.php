<?= tag($file, [], 'h1'); ?>
<?php if(strlen($text) == 0): ?>
	<?= tag("Empty file", ['class'=>'text-muted'], 'pre'); ?>
<?php else: ?>
	<?= tag($text, [], 'pre'); ?>
<?php endif; ?>
