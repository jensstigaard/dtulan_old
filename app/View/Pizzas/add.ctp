<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Add pizza to '.$pizza_category['PizzaCategory']['title']); ?></legend>
		<?php
		echo $this->Form->input('Pizza.title');
		echo $this->Form->input('Pizza.number');
		echo $this->Form->input('Pizza.description');
		echo $this->Form->input('Pizza.available');

		$x = 0;
		foreach ($pizza_category['PizzaCategoryType'] as $type):
			?>
			<div>
				<?php
				echo $type['PizzaType']['title'];
				echo $this->Form->input('PizzaPrice.' . $type['pizza_type_id'] . '.price');
				$x++;
				?>
			</div>
		<?php endforeach; ?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
	<?php // pr($pizza_category); ?>
</div>