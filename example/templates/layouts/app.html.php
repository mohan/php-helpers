<?php

if(strpos($_GET['a'], 'docs') === 0)
	require APP_DIR . '../partials/layout-sidebar.html.php';
else
	require APP_DIR . '../partials/layout-navbar.html.php';
