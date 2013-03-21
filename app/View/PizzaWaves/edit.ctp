<div class="box">
	<?php echo $this->Form->create(); ?>
	<div style="float:right;">
		<ul>
			<li><?php echo $this->Html->link('Back to pizza wave', array('controller' => 'pizza_waves', 'action' => 'view', $pizza_wave['PizzaWave']['id'])); ?></li>
		</ul>
	</div>
	<fieldset>
		<legend><?php echo __('Edit pizza wave at ' . $pizza_wave['LanPizzaMenu']['PizzaMenu']['title'] . ' in ' . $pizza_wave['LanPizzaMenu']['Lan']['title']); ?></legend>
		<?php
		echo $this->Form->input('time_close', array('timeFormat' => '24'));
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>