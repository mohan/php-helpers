<?php
define('LAYOUT_BODY_CLASS', 'extra-body-class');

function _page_title($_pagetitle)
{
	switch($_REQUEST['CURRENT_ACTION']){
		case 'root': $out = 'Example'; break;
		case 'docs': $out = 'Docs - Example'; break;
		case 'docs_view': $out = "Docs - #{$_GET['path']} - Example"; break;
		default: $out = isset($_pagetitle) ? "$_pagetitle - Example" : 'Example'; break;
	}

	return $out;
}
