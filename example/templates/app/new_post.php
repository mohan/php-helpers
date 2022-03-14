<h1>New post</h1>

<div class='panel'>
	<?= formto('create-post') ?>
		<p><input type='text' name='title' placeholder='title' value='<?= $title ?>' /></p>
		<p><textarea name='body' placeholder='body'><?= $body ?></textarea></p>
		<input type='submit' />
	</form>
</div>
