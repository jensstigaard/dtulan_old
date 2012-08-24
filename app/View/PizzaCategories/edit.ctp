<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Edit pizza category'); ?></legend>
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
					$x = 0;
					foreach ($types as $type_id => $type_value):
						?>
					<td>
						<?php echo $this->Form->checkbox('PizzaType.' . $x . '.pizza_type_id', array('value' => $type_id)); ?>
						<?php echo $type_value; ?>
					</td>
						<?php
						$x++;
					endforeach;
					?>
				</tr>
			</table>
		</div>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
<?php // pr($pizza_category);   ?>
</div>