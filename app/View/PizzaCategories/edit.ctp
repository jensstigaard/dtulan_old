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
			<h2>Types associated with category</h2>
			<table>
				<tr>
					<?php
					$x = 0;
					foreach ($types as $type_id => $type_value):
						?>
						<td>
							<?php
							$checkbox_settings = array(
								'value' => $type_id,
							);
							if (isset($types_selected[$type_id])) {
								$checkbox_settings['checked'] = 'checked';
							}
							echo $this->Form->checkbox('PizzaType.' . $x . '.pizza_type_id', $checkbox_settings
							);
							?>
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
	<?php // pr($pizza_category); ?>
	<?php // pr($types_selected); ?>
</div>