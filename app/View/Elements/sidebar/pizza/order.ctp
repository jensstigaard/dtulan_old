<div id="pizza_order">
	<h1>Your order</h1>
	<table>
		<thead>
			<tr>
				<th colspan="3">Pizza</th>
				<th colspan="3">Price</th>
			</tr>

		</thead>
		<tbody>
			<tr>
				<td style="text-align:right;" colspan="3">Total:</td>
				<td style="width:50px;" class="pizza_order_total">0</td>
				<td>DKK</td>
				<td></td>
			</tr>
		</tbody>

	</table>
	<div class="pizza_order_buttons hidden">
		<small>
			<?php echo $this->Js->link('Clear order ' . $this->Html->image('16x16_GIF/action_delete.gif'), '#', array('class' => 'pizza_order_clear', 'escape' => false)); ?>
		</small>
		<?php
		echo $this->Js->link(
				$this->Html->image('16x16_GIF/action_add.gif') . ' Submit order', array(
			'controller' => 'pizza_orders',
			'action' => 'add'
				), array(
			'class' => 'pizza_order_submit',
			'escape' => false
				)
		);
		?>
		<div class="hidden"><?php echo $pizza_wave['PizzaWave']['id']; ?></div>
		<div style="clear:both;"></div>
	</div>
	<div class="pizza_order_sending hidden"></div>
	<div class="pizza_order_success hidden">Pizza order submitted</div>
	<div class="pizza_order_errors hidden"></div>
</div>
