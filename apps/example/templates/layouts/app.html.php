<?php

if(strpos($_GET['a'], 'docs') === 0)
	require APP_DIR . '/../../lib/partials/layout-sidebar.html.php';
else
	require APP_DIR . '/../../lib/partials/layout-navbar.html.php';
