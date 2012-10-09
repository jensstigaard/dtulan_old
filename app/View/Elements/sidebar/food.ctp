<?php if (isset($foods_current_lan_id)): ?>
	<div id="food_order">
		<h1>Your order</h1>
		<table>
			<thead>
				<tr>
					<th colspan="3">Item</th>
					<th colspan="3">Price</th>
				</tr>

			</thead>
			<tbody>
				<tr>
					<td style="text-align:right;" colspan="3">Total:</td>
					<td style="width:50px;" class="order_total">0</td>
					<td>DKK</td>
					<td></td>
				</tr>
			</tbody>

		</table>
		<div class="order_buttons hidden">
			<small>
				<?php echo $this->Js->link('Clear order ' . $this->Html->image('16x16_GIF/action_delete.gif'), '#', array('class' => 'order_clear', 'escape' => false)); ?>
			</small>
			<?php
			echo $this->Js->link(
					$this->Html->image('16x16_GIF/action_add.gif'). ' Submit order', array(
				'controller' => 'food_orders',
				'action' => 'add'
					), array(
				'class' => 'order_submit',
						'escape' => false
					)
			);
			?>
			<div class="hidden"><?php echo $foods_current_lan_id; ?></div>
			<div style="clear:both;"></div>
		</div>
		<div class="order_sending hidden"></div>
		<div class="order_success hidden">Order submitted</div>
		<div class="order_errors hidden"></div>
	</div>
<?php endif; ?>