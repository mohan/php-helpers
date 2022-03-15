<h1>New post</h1>

<div class='panel'>
	<?= formto('create-post') ?>
		<p><?= tag($title, ['type'=>'text', 'name'=>'title', 'placeholder'=>'title'], 'input') ?></p>
		<p><?= tag($body, ['name'=>'body', 'placeholder'=>'body'], 'textarea') ?></p>
		<input type='submit' />
	</form>
</div>
