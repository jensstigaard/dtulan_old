<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Add pizza'); ?></legend>
		<h2>In category:  <?php echo $pizza_category['PizzaCategory']['title']; ?></h2>
		<?php echo $this->Html->link('Back to pizzas', array('controller' => 'pizza_categories', 'action' => 'index')); ?>
		<div>
			<?php
			echo $this->Form->input('Pizza.title');
			echo $this->Form->input('Pizza.number');
			echo $this->Form->input('Pizza.description', array('rows' => 3));
			echo $this->Form->input('Pizza.available');
			?>
			<hr />
			<br />
			<h2>Priser</h2>
			<table>
				<tr>
					<?php
					$x = 0;
					foreach ($pizza_category['PizzaCategoryType'] as $type):
						?>
						<td>
							<div style="text-align:center;margin:0;padding:0;"><?php echo $type['PizzaType']['title']; ?></div>
							<?php
							echo $this->Form->input('PizzaPrice.' . $type['pizza_type_id'] . '.price', array('label' => ''));
							$x++;
							?>
						</td>
					<?php endforeach; ?>
				</tr>
			</table>
		</div>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
	<?php // pr($pizza_category);  ?>
</div>