<?php
echo $this->Html->css('qr_codes', null, array('inline' => false));
?>

<?php foreach ($qr_codes as $i => $code): ?>
	<?php if ($i % $per_line == 0): ?>
		<?php if ($i % $per_page == 0): ?>
			<table style="margin-bottom:50px;">
				<tbody>
				<?php endif; ?>
				<tr>
				<?php endif; ?>
				<td style="padding: 35px;">
					<table>
						<tr>
							<td rowspan="2" style="padding-right: 10px;">
								<?php echo $this->Html->image($code); ?>
							</td>
							<td style="padding-top:10px;">
								<?php echo $this->Html->image('logo_black.png'); ?>
							</td>
						</tr>
						<tr>
							<td>
								# <?php echo $i + 1; ?>
							</td>
						</tr>
					</table>

				</td>
				<?php if ($i % $per_line == $per_line - 1 || $i + 1 == count($qr_codes)): ?>
				</tr>
				<?php if ($i % $per_page == $per_page - 1 || $i + 1 == count($qr_codes)): ?>
				</tbody>
			</table>
			<div style="clear:both;"></div>
			<div class="breakhere"></div>
		<?php endif; ?>
	<?php endif; ?>

<?php endforeach; ?>