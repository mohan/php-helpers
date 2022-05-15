<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <ul>
        <li><?= linkto('root', ['ROOT_URL'=>'/'], 'App') ?></li>
        <li><?= linkto('root', [], 'Dashboard/Root') ?></li>
        <li><?= linkto('posts', [], 'Dashboard/Post') ?></li>
    </ul>
    <?= render_partial($template, $args) ?>
</body>
</html>
