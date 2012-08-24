<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Edit pizza'); ?></legend>
		<h2>In category:  <?php echo $pizza['PizzaCategory']['title']; ?></h2>
		<?php echo $this->Html->link('Back to pizzas', array('controller' => 'pizza_categories', 'action' => 'index')); ?>
		<div>
			<?php
			echo $this->Form->input('title');
			echo $this->Form->input('number');
			echo $this->Form->input('description', array('rows' => 3));
			echo $this->Form->input('available');
			?>
			<hr />
			<br />
			<h2>Priser</h2>
			<table>
				<tr>
					<?php
					$x = 0;
					foreach ($pizza['PizzaCategory']['PizzaType'] as $type):
						?>
						<td>
							<div style="text-align:center;margin:0;padding:0;"><?php echo $type['title']; ?></div>
							<?php
							$price_value = '';
							foreach($pizza['PizzaPrice'] as $price){
								if($price['pizza_type_id'] == $type['id']){
									$price_value = $price['price'];
								}
							}
							echo $this->Form->input('PizzaPrice.' . $type['id'] . '.price', array('label' => '', 'value' => $price_value));
							$x++;
							?>
						</td>
					<?php endforeach; ?>
				</tr>
			</table>
		</div>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
	<?php // pr($pizza);  ?>
</div>