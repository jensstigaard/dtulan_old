<?php foreach ($qr_codes as $i => $code): ?>
	<?php if ($i % $per_line == 0): ?>
		<?php if ($i % $per_page == 0): ?>
			<table style="margin-bottom:50px;">
				<tbody>
				<?php endif; ?>
				<tr>
				<?php endif; ?>
				<td style="padding: 45px 20px;"><?php echo $this->Html->image($code); ?></td>
				<?php if ($i % $per_line == $per_line-1 || $i+1 == count($qr_codes)): ?>
				</tr>
				<?php if ($i % $per_page == $per_page-1 || $i+1 == count($qr_codes)): ?>
				</tbody>
			</table>
	<div class="breakhere"></div>
		<?php endif; ?>
	<?php endif; ?>

<?php endforeach; ?>