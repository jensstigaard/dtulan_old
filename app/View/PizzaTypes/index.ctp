<div>
	<h1>Pizza types known</h1>
	<table>
		<thead>
			<tr>
				<th>Title</th>
				<th>Short title</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($pizza_types as $pizza_type): ?>
				<tr>
					<td><?php echo $pizza_type['PizzaType']['title']; ?></td>
					<td><?php echo $pizza_type['PizzaType']['title_short']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div>
	<?php echo $this->Form->create(); ?>
	<fieldset>
		<legend><?php echo __('New pizza type'); ?></legend>
		<?php
		echo $this->Form->input('PizzaType.title');
		echo $this->Form->input('PizzaType.title_short');
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>