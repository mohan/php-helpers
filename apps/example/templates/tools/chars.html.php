<h1>256 Character Set</h1>
<p></p>

<table class='table'>
<thead>
    <tr>
        <th>Code</th>
        <th>Symbol</th>
        <th>HTML Entity</th>
        <th>URL Encoded String</th>
    </tr>
</thead>
<tbody>
<?php for ($i=0; $i < 256; $i++): ?>
    <tr>
        <td>
            <?= $i ?>
        </td>
        <td>
            <?= input(utf8_encode(chr($i)), ['readonly'=>true, '_no-auto'=>true]) ?>
        </td>
        <td>
            <?= input(htmlentities(utf8_encode(chr($i))), ['readonly'=>true, '_no-auto'=>true]) ?>
        </td>
        <td>
            <?= input(urlencode(chr($i)), ['readonly'=>true, '_no-auto'=>true]) ?>
        </td>
    </tr>
<?php endfor; ?>
</tbody>
</table>


<style type="text/css">
    table input{
        font-size:2em;
        width:200px;
        border:0;
        background:transparent;
        text-align:center;
    }
</style>
