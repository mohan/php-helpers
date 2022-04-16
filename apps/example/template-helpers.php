<?php

function _pagetitle($_pagetitle)
{
	switch($_REQUEST['CURRENT_ACTION']){
		case 'root': $out = 'Example'; break;
		case 'docs': $out = 'Docs - Example'; break;
		case 'docs/view': $out = $_pagetitle . " - Docs - Example"; break;
		default: $out = isset($_pagetitle) ? "$_pagetitle - Example" : 'Example'; break;
	}

	return $out;
}
