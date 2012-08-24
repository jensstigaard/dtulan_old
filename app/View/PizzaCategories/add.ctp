<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Add pizza category'); ?></legend>
		<?php echo $this->Html->link('Back to pizzas', array('controller' => 'pizza_categories', 'action' => 'index')); ?>
		<div>
			<?php
			echo $this->Form->input('PizzaCategory.title');
			echo $this->Form->input('PizzaCategory.description', array('rows' => 3));
			echo $this->Form->input('PizzaCategory.available');
			?>
			<hr />
			<table>
				<tr>
					<?php
					echo $this->Form->input('PizzaCategoryType.pizza_type_id', array('options' => $types, 'multiple' => 'multiple', 'label' => 'Pizza types for this category'))
					?>
				</tr>
			</table>
		</div>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
	<?php // pr($pizza_category);  ?>
</div>