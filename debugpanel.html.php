<?php if(defined('APP_ENV_IS_DEVELOPMENT') && APP_ENV_IS_DEVELOPMENT): ?>
	<div style='text-align:right;'>
		<a id='toggle-debug-info' href='#toggle-debug-info' style=''>Show/Hide Debug Panel</a>
	</div>
	<div id='debug-info-container' style='display:none;'>
		<h2>Debug Information Panel</h2>
		<div style='padding: 20px;'><?= $_REQUEST['DEBUG_REQUEST_ARGS_HTML'] ?></div>
	</div>

	<style type="text/css">
		#toggle-debug-info{
			display:block;
			font-size:10px;
			padding-right:5px;
			text-align: center;
			background:#f6c457;
		}
		#debug-info-container{
			margin-top:-15px;
			border-top:1px solid #f6c457;
			border-bottom:2px solid #f6c457;
			background:#efefef;
			font-size:12px;
		}
		#debug-info-container h2{
			background:#f6c457;
			padding:10px 20px 10px 20px;
			margin:0;
			text-shadow:0 1px #f5C356;
		}
		#debug-info-container textarea{
			border: 0px solid #f6c457;
			border-radius: 2px;
			margin-bottom: 1px;
			height: 30px;
			width:100%;
			height:40px;
			background:transparent;
		}
		#debug-info-container table{
			width: 100%;
		}
		#debug-info-container table th:first-child{
			width: 350px;
		}
		#debug-info-container table th{
			background: #f6c457;
		}
		#debug-info-container table td:first-child{
			background: #faedcf;
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
