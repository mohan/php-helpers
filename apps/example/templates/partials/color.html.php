<div class='d-inline-block' style='margin:0 20px 30px 0; border: 1px solid #333;'>
	<?= tag('', ['class'=>'d-block', 'style'=>"width:165px; height: 165px; background:{$color[0]};"]) ?>
	<div style='padding: 10px; background: #333;'>
		<?= tag($color[0], ['type'=>'text', 'style'=>'padding: 0; width: 130px; background: transparent; color: #fff; border: 0;'], 'input') ?>
		<br/>
		<?= tag("rgb({$color[1]})", ['type'=>'text', 'style'=>'padding: 0; width: 130px; background: transparent; color: #fff; border: 0;'], 'input') ?>
	</div>
</div>
