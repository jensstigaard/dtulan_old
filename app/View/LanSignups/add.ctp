<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Signup for ' . $lan['Lan']['title']); ?></legend>
		<table>
			<tbody>
				<tr>
					<td>Price:</td>
					<td><?php echo $lan['Lan']['price']; ?> DKK</td>
				</tr>
				<tr>
					<td>Start-date:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_start']); ?></td>
				</tr>
				<tr>
					<td>End-date:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_end']); ?></td>
				</tr>
			</tbody>
		</table>

		<?php if ($lan['Lan']['need_physical_code']) : ?>
			<div>
				<?php echo $this->Form->input('code'); ?>
				<p style="padding-left: 5px;">A code is needed to complete sign-up for this event.</p>
			</div>
		<?php endif; ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>