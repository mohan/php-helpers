<h2>Search</h2>
<?= formto('search', [], ['method'=>'get'], [
	'a'		=> ['value'=>'search', 'type'=>'hidden'],
	'query' => ['value'=>$query, 'placeholder'=>'Seach Query']
]) ?>

<?php if($results): ?>
	<div class='results'>
		<?php foreach($results as $result): ?>
			<div class='result panel'>
				<?= render_markdown($result['title']) ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<div class='panel text-center fg-7'>
		<p>Enter your query above to start searching.</p>
	</div>
<?php endif; ?>
