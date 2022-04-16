<h1>New post</h1>

<h3>Form helper example</h3>
<?= formto('create-post', [], ['class'=>'panel'], [
		'title' => ['value'=>$title, 'label'=>'Title'],
		'body' => ['value'=>$body, 'label'=>'Body', 'tag'=>'textarea'],
		'submit' => ['value'=>'Submit', 'type'=>'submit', 'tag'=>'button']
	])
?>

<hr/>

<h3>Form helper with only fields generated</h3>
<form action="<?= urltopost('create-post') ?>" method="post" class="panel">
	<?= form_field('create-post-2', 'title', ['value'=>$title, 'label'=>'Title']) ?>
	<?= form_field('create-post-2', 'body', ['value'=>$body, 'label'=>'Body', 'tag'=>'textarea']) ?>
	<?= form_field('create-post-2', 'submit', ['value'=>'Submit', 'type'=>'submit', 'tag'=>'button']) ?>
</form>
