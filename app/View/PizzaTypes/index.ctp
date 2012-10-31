<div>
	<h1>Pizza types known</h1>
	<ul>
		<?php foreach ($pizza_types as $pizza_type): ?>
		<li><?php echo $pizza_type['PizzaType']['title']; ?></li>
	<?php endforeach; ?>
	</ul>
</div>
<div>
	<?php echo $this->Form->create(array('action' => 'add')); ?>
    <fieldset>
        <legend><?php echo __('New pizza type'); ?></legend>
		<?php
		echo $this->Form->input('title');
		echo $this->Form->input('title_short');
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>