<h2 class='fg-3 border-bottom' style='font-size:1em;margin:0;'><?= $_pagetitle ?></h2>

<?php
	if(
		(isset($defined_constants) || isset($function_names) || isset($internal_function_names)) &&
		(sizeof($defined_constants) > 0 || sizeof($function_names) > 0 || sizeof($internal_function_names) > 0)
	):
?>
	<div class='panel'>
		<?php if(sizeof($defined_constants) > 0): ?>
			<h3 style='font-size:0.9em;'>Constants</h3>
			<ol style='font-size:0.9em;'>
			<?php foreach($defined_constants as $i => $defined_constant): ?>
				<?php
					$text = str_replace($defined_constant, "<span id='$defined_constant'>$defined_constant</span>", $text);
				?>
				<li><?= linkto('', $defined_constant, ['_hash'=>$defined_constant]) ?></li>
			<?php endforeach; ?>
			</ol>
		<?php endif; ?>

		<?php if(sizeof($function_names) > 0): ?>
			<h3 style='font-size:0.9em;'>Functions</h3>
			<ol style='font-size:0.9em;'>
			<?php foreach($function_names as $name => $args): ?>
				<?php
					$text = str_replace(
						"function&nbsp;</span><span style=\"color: #0000BB\">{$name}</span>",
						"function&nbsp;</span><span style=\"color: #0000BB\" id='{$name}'>{$name}</span>",
						$text
					);
				?>
				<li>
					<?= linkto('', $name, ['_hash'=>$name]) ?>
					<p class='fg-5'><?= $args ? $args : 'No Arguments' ?></p>
				</li>
			<?php endforeach; ?>
			</ol>
		<?php endif; ?>

		<?php if(sizeof($internal_function_names) > 0): ?>
			<h3 style='font-size:0.9em;'>Internal Functions</h3>
			<ol style='font-size:0.9em;'>
			<?php foreach($internal_function_names as $name => $args): ?>
				<?php
					$text = str_replace(
						"function&nbsp;</span><span style=\"color: #0000BB\">{$name}</span>",
						"function&nbsp;</span><span style=\"color: #0000BB\" id='{$name}'>{$name}</span>",
						$text
					);
				?>
				<li>
					<?= linkto('', $name, ['_hash'=>$name]) ?>
					<p class='fg-5'><?= $args ? $args : 'No Arguments' ?></p>
				</li>
			<?php endforeach; ?>
			</ol>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?=
	pre_($text, [
			'class' => 'border-top',
			'style' =>
				($raw ? '' : 'font-size:1.25em;') .
				'border-left:1px solid #ccc;' .
				'padding-left:20px;' .
				($raw ? '' : 'padding-top:0;') .
				'margin-top:20px;'
		]
	)
?>
