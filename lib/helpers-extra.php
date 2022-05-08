<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress

define('_PHP_HELPERS_EXTRA_IS_DEFINED', true);
define('APP_ENV_IS_DEVELOPMENT', getenv('APP_ENV_IS_DEVELOPMENT') == 'true');

// 
// Tag helpers
// 
function h1             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h1',           true  );   }
function h1_            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h1',           false );   }
function h2             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h2',           true  );   }
function h2_            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h2',           false );   }
function h3             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h3',           true  );   }
function h3_            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h3',           false );   }
function h4             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h4',           true  );   }
function h4_            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h4',           false );   }
function h5             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h5',           true  );   }
function h5_            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h5',           false );   }
function h6             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h6',           true  );   }
function h6_            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'h6',           false );   }
function p              ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'p',            true  );   }
function p_             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'p',            false );   }
function div            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'div',          true  );   }
function div_           ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'div',          false );   }
function span           ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'span',         true  );   }
function span_          ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'span',         false );   }
function pre            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'pre',          true  );   }
function pre_           ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'pre',          false );   }
function blockquote     ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'blockquote',   true  );   }
function blockquote_    ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'blockquote',   false );   }
function img            ( $src,  $attrs=[] )       {   return tag( $src,  $attrs, 'img',          true  );   }
function iframe         ( $src,  $attrs=[] )       {   return tag( $src,  $attrs, 'iframe',       true  );   }
function li             ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'li',           true  );   }
function li_            ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'li',           false );   }
function input          ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'input',        true  );   }
function textarea       ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'textarea',     true  );   }
function label          ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'label',        true  );   }
function label_         ( $html, $attrs=[] )       {   return tag( $html, $attrs, 'label',        false );   }




if(APP_ENV_IS_DEVELOPMENT){
    function _print_debugpanel($args){
        _print_debug_args('Render $args', $args);

        if(isset($_REQUEST['_REQUEST_ARGS_CURRENT_ACTION'])) {
            _print_debug_current_action(...$_REQUEST['_REQUEST_ARGS_CURRENT_ACTION']);
        }

        if(sizeof($_GET) > 0) _print_debug_args('$_GET params', $_GET);
        if(sizeof($_POST) > 0) _print_debug_args('$_POST params', $_POST);
        if(sizeof($_COOKIE) > 0) _print_debug_args('$_COOKIE params', $_COOKIE);
        _print_debug_args('$_REQUEST params', $_REQUEST);
        if(md5_cookie_get('flash')) _print_debug_args('$_REQUEST[\'flash\']', ['flash'=>md5_cookie_get('flash')]);

        _print_debug_args('PHP Variables $_SERVER',$_SERVER);
    }

    function _print_debug_current_action($method_name, $required_params, $action_name)
    {
        $args = [
            'ROOT_URL' => ROOT_URL,
            "\$_REQUEST['CURRENT_METHOD']" => $_REQUEST['CURRENT_METHOD'],
            "\$_REQUEST['CURRENT_ACTION']" => $_REQUEST['CURRENT_ACTION'],
            "\$_REQUEST['LAYOUT']" => $_REQUEST['LAYOUT'],
            "\$_REQUEST['TEMPLATE']" => $_REQUEST['TEMPLATE'],
            "\$_REQUEST['ACTION_ID']" => $_REQUEST['ACTION_ID'],
            'Action function' => $action_name,
            'Required params for action' => $required_params
        ];

        _print_debug_args('Current Action', $args);
    }


    function _print_debug_args($name, $args)
    {
        if($name) echo h3($name);

        echo tag_table(['Param', 'Value'], $args, [], function($row, $header_name, $row_key){
            switch ($header_name) {
                case 'Param':
                    return $row_key;
                
                case 'Value':
                    return is_string($row) ? tag($row, ['rows'=>30], strlen($row) > 500 ? 'textarea' : 'div') :
                        pre( print_r($row, true) );
            }
        });
    }
}

// 
// END Debug helpers
// 













// 
// Markdown
// 

function render_markdown($text, $attrs=[], $enable_shortcodes=false)
{
    static $patterns = [[
        "/^(\t*)---$/",                                                 // hr
        "/\*\*\*([^*]+)\*\*\*/",                                        // bold italic
        "/\*\*([^*]+)\*\*/",                                            // italic
        "/([^*\t])\*([^*]+)\*/",                                        // bold
        "/~~([^~]+)~~/",                                                // strikethrough
        "/\[([^\]]+)\]\((\/|#|\?|[a-z]+:\/\/)([^\)]*)\)/",              // link with text
        "/\((\/|#|\?|[a-z]+:\/\/)([^\)]*)\)/",                          // link without text
        "/`([^`]+)`/",                                                  // code
        "/^(\t*)-\s\[x\]\s(.+)$/",                                      // Task list item checked
        "/^(\t*)-\s\[X\]\s(.+)$/",                                      // Task list item checked and striked
        "/^(\t*)-\s\[!\]\s(.+)$/",                                      // Task list item striked unchecked
        "/^(\t*)-\s\[\s\]\s(.+)$/",                                     // Task list unchecked
        "/^(\t*)\*\s/",                                                 // Bullet list
        "/^(\t*)\-\s/",                                                 // Dash list
        "/^(\t*)(\d+\.)\s/"                                             // Numbered list
    ],[
        "$1<hr/>",
        "<strong><em>$1</em></strong>",
        "<em>$1</em>",
        "$1<strong>$2</strong>",
        "<strike>$1</strike>",
        "<a href='$2$3'>$1</a>",
        "<a href='$1$2'>$1$2</a>",
        "<span class='md-code'>$1</span>",
        "$1<span class='md-task-list md-task-list-checked'><input type='checkbox' checked='checked' disabled='true' /> $2</span>",
        "$1<span class='md-task-list md-task-list-checked-striked'><input type='checkbox' checked='checked' disabled='true' /> <strike>$2</strike></span>",
        "$1<span class='md-task-list md-task-list-unchecked-striked'><input type='checkbox' disabled='true' /> <strike>$2</strike></span>",
        "$1<span class='md-task-list md-task-list-unchecked'><input type='checkbox' disabled='true' /> $2</span>",
        "$1<span class='md-list-bullet'>&bull;</span> ",
        "$1<span class='md-list-dash'>&ndash;</span> ",
        "$1<span class='md-list-number'>$2</span> "
    ]];
    
    $out = '';
    // Todo: Optimize, use substr.
    $lines = explode("\n", $text);
    $is_codeblock = false;
    $tab_size_for_current_block = 0;
    $codeblock_attr = 'raw';
    $data_table_header = [];
    $data_table = [];
    $data_table_i = 0;
    foreach ($lines as $i => $line) {
        $matches = [];
        if(preg_match("/^(\t*)```([[:alnum:]\s]*)$/", $line, $matches)){
            if($is_codeblock){
                if(_str_contains($codeblock_attr, 'table')){
                    $out .= tag_table($data_table_header, $data_table);
                    $data_table_header = [];
                    $data_table = [];
                    $data_table_i = 0;
                }

                $is_codeblock = false;
                $tab_size_for_current_block = 0;
                $codeblock_attr = 'raw';
                $out .= "</div>\n";
            } else {
                $is_codeblock = true;
                $tab_size_for_current_block = strlen($matches[1]);
                $codeblock_attr = str_replace(' ', ' md-codeblock-', $matches[2]);
                if(!$codeblock_attr) $codeblock_attr = 'plain';
                $class_list = "tab-count-$tab_size_for_current_block md-codeblock-" . $codeblock_attr;
                $out .= "<div class='md-codeblock $class_list'>\n";
            }
            continue;
        }

        if($is_codeblock && _str_contains($codeblock_attr, 'table')){
            if($data_table_i == 0) $data_table_header = str_getcsv(trim($line), '|');
            else $data_table[] = str_getcsv(trim($line), '|');

            $data_table_i++;
            continue;
        }

        $line = htmlentities($line);

        if($is_codeblock && !_str_contains($codeblock_attr, 'raw')){
            $line = preg_replace($patterns[0], $patterns[1], $line);
        }

        if(!$is_codeblock){
            $line = preg_replace($patterns[0], $patterns[1], $line);
        }

        if(preg_match("/^\t*(#{1,5})\s(.+)$/", $line, $matches)){
            // headings
            $_tag = 'h' . strlen($matches[1]);
            $_id = strtolower(preg_replace("/[^a-zA-Z\d]/", '-', $matches[2]));
            $out .= "<$_tag id='$_id' class='md-heading'><a class='md-hash-link' href='#$_id'>\n" . htmlentities($matches[2]) . "\n</a></$_tag>\n";
            continue;
        }

        $tabs = '';
        if(preg_match("/^\t+/", $line, $matches)){
            $tabs = " class='tab-count-" . (strlen($matches[0]) - $tab_size_for_current_block) . "'";
        }

        $_tag = _str_contains($line, '<hr/>') ? 'div' : 'p';
        $line = strlen($line) == 0 ? "<p class='md-br'></p>\n" : "<$_tag$tabs>\n" . $line . "\n</$_tag>\n";

        if($enable_shortcodes && $shortcode_line = process_shortcodes($line)){
            $line = $shortcode_line;
        }

        $out .= $line;
    }

    $attrs_str = sizeof($attrs) == 0 ? "class='markdown'" : '';
    foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

    return "\n<!-- Markdown start -->\n<div $attrs_str>\n$out\n</div>\n<!-- Markdown end -->\n";
}



// 
// END Markdown
// 










// 
// Shortcodes
// 

// Process all shortcodes using respective functions and replace with return values.
function process_shortcodes($text)
{
    static $shortcode_list_regex = false;

    if(!$shortcode_list_regex) {
        $shortcodes_list = preg_replace("/[^[:alnum:]_-]/", "_", _shortcodes_list());
        $shortcode_list_regex = "/\[(" . join('|', $shortcodes_list) . ")([^\]]*)\]/";
    }

    // start with [
    // \[([a-z]+) = name
    // ([^\]]*) = args_str
    if(!preg_match_all($shortcode_list_regex, $text, $matches)) return false;

    
    $shortcodes_matches = [];
    $shortcode_replacements = [];
    foreach ($matches[0] as $key => $match) {
        $full_shortcode = trim($matches[0][$key]);
        $name = $matches[1][$key];
        $args_str = $matches[2][$key];
        
        $args = [];
        if(_str_contains($args_str, '=')){
            $args[0] = $args_str;
        } else {
            if(preg_match_all('/([a-z]+)="?([^"]+)"?/', $args_str, $args_matches)){
                $arg_counts = $args_matches[1] ? array_count_values($args_matches[1]) : [];

                foreach ($args_matches[0] as $arg_key => $arg_value) {
                    $key = $args_matches[1][$arg_key];
                    $value = $args_matches[2][$arg_key];
                    
                    if($arg_counts[$key] > 1) $args[$key][] = $value;
                    else $args[$key] = $value;
                }
            }
        }

        // Todo: Optimize, insert using substr.
        $shortcodes_matches[] = '/' . preg_quote($full_shortcode) . '/';
        $shortcode_replacements[] = call_user_func("shortcode_" . str_replace('-', '_', $name), $args);
    }

    return preg_replace($shortcodes_matches, $shortcode_replacements, $text, 1);
}



// 
// END Shortcodes
// 













// 
// Config file helpers
//

// Defines constants CONFIG_NAME from config ini file
function filter_set_config($filepath, $prefix='CONFIG_')
{
    $config = parse_ini_file($filepath);

    foreach ($config as $key => $value) {
        define(strtoupper($prefix . $key), $value);
    }
}

// 
// END Config file helpers
//
