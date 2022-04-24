<?php if(defined('APP_ENV_IS_DEVELOPMENT') && APP_ENV_IS_DEVELOPMENT && defined('_PHP_HELPERS_EXTRA_IS_DEFINED') && _PHP_HELPERS_EXTRA_IS_DEFINED): ?>
	<a id='toggle-debug-info' href='#toggle-debug-info'>Show/Hide Debug Panel</a>
	<div id='debug-info-container'>
		<?= _print_debugpanel() ?>
	</div>

	<style type="text/css">
		#toggle-debug-info{
			display:block;
			font-size:10px;
			text-align: center;
			background:#f6c457;
		}
		#debug-info-container{
			border-top:1px solid #f6c457;
			border-bottom:2px solid #f6c457;
			background:#efefef;
			font-size:12px;
			font-family: 'arial';
			padding: 20px 40px;
		}
		#debug-info-container table{
			width: 100%;
			font-size: 13px;
		}
		#debug-info-container table th:first-child{
			width: 350px;
		}
		#debug-info-container table th{
			background: #f6c457;
			padding: 5px;
		}
		#debug-info-container table td:first-child{
			background: #faedcf;
			padding-left: 10px;
		}
		#debug-info-container table td{
			border-bottom: 1px solid #f6c457;
			padding: 5px;
		}
	</style>
	<script type="text/javascript">
		(function(link, debug_info_container){
			debug_info_container.style.display = 'none';
			link.addEventListener('click', function(e){
				e.preventDefault();
				debug_info_container.style.display = debug_info_container.style.display == 'none' ? 'block' : 'none';
			});
		})(document.getElementById('toggle-debug-info'), document.getElementById('debug-info-container'));
	</script>
<?php endif; ?>
