<h1>New post</h1>

<?= formto('create-post', [], ['class'=>'panel'], [
		['tag'=>'input', 'type'=>'text', 'name'=>'title', 'value'=>$title, 'placeholder'=>'Title', 'title'=>'Title'],
		['tag'=>'textarea', 'name'=>'body', 'value'=>$body, 'placeholder'=>'Body', 'title'=>'Body'],
		['tag'=>'button', 'type'=>'submit', 'value'=>'Submit']
	])
?>
