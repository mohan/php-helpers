<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress

if(defined('APP_ENV_IS_DEVELOPMENT') && APP_ENV_IS_DEVELOPMENT && defined('_PHP_HELPERS_EXTRA_IS_DEFINED') && _PHP_HELPERS_EXTRA_IS_DEFINED):
?>
	<?php if(!defined('DEBUG_PANEL_LINK_ECHOED')): define('DEBUG_PANEL_LINK_ECHOED', true) ?>
		<style type="text/css">
			#show-debug-panel{
				display:block;
				font-size:10px;
				text-align: center;
				background:#f6c457;
			}
			#debug-panel{
				border-top:2px solid #f6c457;
				background:#efefef;
				font-size:12px;
				font-family: 'arial';
				padding: 20px 10px;
			}
			#debug-panel table{
				margin-bottom: 30px;
				width: 100%;
				font-size: 13px;
			}
			#debug-panel textarea.autoexpand:focus{
				min-height: 300px;
			}
			#debug-panel table th:first-child{
				width: 300px;
			}
			#debug-panel table th{
				background: #f6c457;
				padding: 5px;
			}
			#debug-panel table td:first-child{
				background: #faedcf;
				padding-left: 10px;
			}
			#debug-panel table td{
				border-bottom: 1px solid #f6c457;
				padding: 5px;
			}
		</style>
		<a id='show-debug-panel' href='#debug-panel'>Debug Panel</a>
	<?php else: ?>
		<div id='debug-panel'>
			<h2>Debug Panel</h2>
			<?= _print_debugpanel($args) ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
