<?php

function _nested_files_list($dir, $hidden=false)
{
	if(!$dir) return false;

	$files = scandir($dir);

	$basename = basename($dir);
	$list = [];
	foreach ($files as $file) {
		if(
			$file == '.' || $file == '..' ||
			$file[0] == '.'
		) continue;

		$path = _path_join($dir, $file);
		
		if(is_file($path)) {
			$list["$basename"][] = [
				'_leaf'	=> true,
				'path'	=>	$path,
				'name'	=>	$file
			];
		} else if(is_dir($path)) {
			$list["$basename"][] = _nested_files_list($path);
		}
	}

	return $list;
}



function _recursive_print_list($tree, $li_class='', $ul_class='', $root_cb=false, $item_cb=false, $level=0)
{
	$out = '';

	foreach ($tree as $key => $value) {
		$out .= "<li class='item level-$level $li_class'>";

		if(is_array($value) && !isset($value['_leaf'])) {
			$out .= $root_cb ? call_user_func($root_cb, $key) : (is_string($key) ? "<div class='root-name'><span class='root-indicator'></span>$key</div>" : '') .
					"<ul class='root $ul_class'>" .
						_recursive_print_list($value, $li_class, $ul_class, $root_cb, $item_cb, $level + 1) .
					"</ul>";
		} else {
			$out .= $item_cb ? call_user_func($item_cb, $value) : "<div class='item-value'>$value</div>";
		}

		$out .= "</li>\n";
	}

	return $out;
}
