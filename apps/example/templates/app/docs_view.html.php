<?= render_markdown($text, ['id'=>strtolower($_pagetitle), 'class'=>'markdown'], true); ?>

<style type="text/css">
#php-helpers-catalyst-book .md-section{
    background: #fafafa;
    border: 1px solid #aaa;
    margin: 10px 0% 30px 0%;
    padding: 20px 2% 20px 3%;
}

#php-helpers-catalyst-book .md-section:first-child{
    background: transparent;
    border: none;
    padding: 0;
}

#php-helpers-catalyst-book .md-hr{
    display: none;
}

@media print {
    #php-helpers-catalyst-book .md-section{
        background: transparent;
        border: 1px solid #ccc;
    }
}
</style>
