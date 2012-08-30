<div class="form">
	<?php echo $this->Form->create(); ?>
	<div style="float:right;">
		<?php echo $this->Html->link('Go to '. $lan['Lan']['title'], array('controller' => 'lans', 'action'=>'view', $lan['Lan']['id'])); ?>
		| <?php echo $this->Html->link('Go to Pizzas', array('controller' => 'pizza_categories', 'action'=>'index')); ?>
	</div>
    <fieldset>
        <legend><?php echo __('Create PizzaWave for '. $lan['Lan']['title']); ?></legend>
		<?php
		echo $this->Form->input('time_start', array('timeFormat' => '24'));
		echo $this->Form->input('time_end', array('timeFormat' => '24'));
		echo $this->Form->input('status');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>