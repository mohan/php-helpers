<?php

function _pagetitle($_pagetitle, $args)
{
    switch($_REQUEST['CURRENT_ACTION']){
        case 'root': $out = 'Example'; break;
        case 'docs': $out = 'Docs - Example'; break;
        case 'docs/view': $out = $_pagetitle . " - Docs - Example"; break;
        case 'book':    $out = 'PHP Helpers Catalyst Book'; break;
        default: $out = isset($_pagetitle) ? "$_pagetitle - Example" : 'Example'; break;
    }

    return $out;
}
