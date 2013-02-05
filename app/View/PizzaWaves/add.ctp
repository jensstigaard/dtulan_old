<div>
	<?php echo $this->Form->create(); ?>
	<div style="float:right;">
		<ul>
			<li><?php echo $this->Html->link('View pizza menu', array('controller' => 'lan_pizza_menus', 'action' => 'view', $lan_pizza_menu['LanPizzaMenu']['id'])); ?></li>
		</ul>
	</div>
    <fieldset>
        <legend><?php echo __('Add pizza wave for ' . $lan_pizza_menu['PizzaMenu']['title'] . ' at ' . $lan_pizza_menu['Lan']['title']); ?></legend>
		<?php
		echo $this->Form->input('time_close', array('timeFormat' => '24'));
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>