<?php if(defined('APP_ENV_IS_DEVELOPMENT') && APP_ENV_IS_DEVELOPMENT && defined('_PHP_HELPERS_EXTRA_IS_DEFINED') && _PHP_HELPERS_EXTRA_IS_DEFINED): ?>
	<a id='toggle-debug-info' href='#toggle-debug-info'>Show/Hide Debug Panel</a>
	<div id='debug-info-container' style='display:none;'>
		<div style='padding: 20px;'><?= _print_debugpanel() ?></div>
	</div>

	<style type="text/css">
		#toggle-debug-info{
			display:block;
			font-size:10px;
			text-align: center;
			background:#f6c457;
			text-align: center;
		}
		#debug-info-container{
			border-top:1px solid #f6c457;
			border-bottom:2px solid #f6c457;
			background:#efefef;
			font-size:12px;
			font-family: 'arial';
		}
		#debug-info-container h2{
			background:#f6c457;
			padding:10px 20px 10px 20px;
			margin:0;
			text-shadow:0 1px #f5C356;
		}
		#debug-info-container h3:first-child{
			border: 0;
			padding-top: 0;
			margin-top: 0;
		}
		#debug-info-container h3{
			border-top: 1px solid #cfcfcf;
			margin-top: 20px;
			padding-top: 20px;
		}
		#debug-info-container textarea{
			border: 0;
			margin-bottom: 1px;
			height: 30px;
			width:100%;
			height:40px;
			background:transparent;
			font-size: 12px;
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
			link.addEventListener('click', function(e){
				e.preventDefault();
				debug_info_container.style.display = debug_info_container.style.display == 'none' ? 'block' : 'none';
			});
		})(document.getElementById('toggle-debug-info'), document.getElementById('debug-info-container'));
	</script>
<?php endif; ?>
