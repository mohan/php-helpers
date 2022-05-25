<html>
<head>
    <title>Structure Only</title>
    <link rel="stylesheet" type="text/css" href="/assets/style.css">
</head>
<body class='layout-navbar'>
    <div id='navbar'>
        <h2>Structure Only Application Example</h2>
        <ul>
            <li>
                <a href='/' class='<?= $action_name == 'root' ? 'current-uri-link' : '' ?>'>
                    Home
                </a>
            </li>
            <li>
                <a href='/?<?= http_build_query(['a'=>'page1']) ?>' class='<?= $action_name == 'page1' ? 'current-uri-link' : '' ?>'>
                    Page 1
                </a>
            </li>
            <li>
                <a href='/?<?= http_build_query(['a'=>'page2']) ?>' class='<?= $action_name == 'page2' ? 'current-uri-link' : '' ?>'>
                    Page 2
                </a>
            </li>
            <li>
                <a href='/?<?= http_build_query(['a'=>'page3']) ?>' class='<?= $action_name == 'page3' ? 'current-uri-link' : '' ?>'>
                    Page 3
                </a>
            </li>
            <li>
                <a href='/?<?= http_build_query(['a'=>'404']) ?>'>
                    404
                </a>
            </li>
        </ul>
        <ul>
            <li>
                <a href='/?<?= http_build_query(['a'=>'login', 'p'=>'secure-page1']) ?>' class='<?= $action_name == 'secure-page1' ? 'current-uri-link' : '' ?>'>
                    Secure Page 1
                </a>
            </li>
            <li>
                <a href='/?<?= http_build_query(['a'=>'login', 'p'=>'secure-page2']) ?>' class='<?= $action_name == 'secure-page2' ? 'current-uri-link' : '' ?>'>
                    Secure Page 2
                </a>
            </li>
        </ul>
    </div>

    <div id="main">
        <?php include "../templates/app/$action_name.html.php"; ?>
    </div>
</body>
</html>
