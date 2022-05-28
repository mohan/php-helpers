<?= render_markdown($text, ['id'=>_to_id($_pagetitle), 'class'=>'markdown' . ($_pagetitle == 'Php-helpers-catalyst-book' ? ' section-pages':'')], true); ?>

<style type="text/css">
    body.php-helpers-catalyst-book{
        background: #f0f0f0;
    }

    #php-helpers-catalyst-book{
        width: 100%;
        padding-right: 12%;
    }
</style>
