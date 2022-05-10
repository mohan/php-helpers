<ul id='files-list' class='list-nested list-style-none'>
	<?=
	_recursive_print_list($files, '', '', false, function($value){
		return linkto('viewer', ['file'=>$value['path']], $value['name'], ['target'=>'file', 'class'=>'item-value file']);
	});
	?>
</ul>

<?=
formto('files', [], ['method'=>'get'], [
	'a' => [ 'type' => 'hidden', 'value' => 'files' ],
	'dir' => [ 'tag' => 'textarea', 'value'=>$dir, 'placeholder'=>'Project Directory' ],
	'submit' => [ 'type' => 'submit', 'value' => 'Refresh Files', 'class'=>'d-block w-100' ]
]);
?>
