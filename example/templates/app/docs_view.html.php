<?php if($raw): ?>
	<?= tag($text, ['style'=>'tab-size:4;'], 'pre') ?>
<?php else: ?>
	<?= render_markdown($text, [], true); ?>
<?php endif; ?>
