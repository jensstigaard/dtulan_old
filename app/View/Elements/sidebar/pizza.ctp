<?php if (isset($waves) && count($waves)): ?>
	<div id="pizza_waves">
		<h1 style="padding:0;">Pizza Waves available:</h1>
		<?php if ($current_wave == ''): ?>
			<div class="notice" style="font-size:11pt; padding:5px;">
				Choose a wave below to order pizzas.
			</div>
		<?php endif; ?>
	</div>
	<?php
	$last_date = '';
	foreach ($waves as $wave):
		$this_date = $this->Time->format('Y-m-d', $wave['PizzaWave']['time_start']);
		?>
		<?php if ($last_date != $this_date): ?>
			<?php if ($last_date != '') : ?>
				</ul>
				</div>
			<?php endif; ?>
			<div>
				<h3>
					<?php
					if ($this->Time->isToday($wave['PizzaWave']['time_start'])) {
						echo'Today';
					} elseif ($this->Time->isTomorrow($wave['PizzaWave']['time_start'])) {
						echo'Tomorrow';
					} elseif ($this->Time->isThisWeek($wave['PizzaWave']['time_start'])) {
						echo $this->Time->format('l', $wave['PizzaWave']['time_start']);
					} else {
						echo $this->Time->format('D, M jS', $wave['PizzaWave']['time_start']);
					}
					?>
				</h3>
				<ul>
					<?php $last_date = $this_date; ?>
				<?php endif; ?>
				<li>
					<?php
					$content = '';
					if ($wave['PizzaWave']['id'] == $current_wave['PizzaWave']['id']) {
						$content .= $this->Html->image('16x16_GIF/action_check.gif') . ' ';
					}
					$content.= $this->Time->format('H:i', $wave['PizzaWave']['time_start']) . ' - ' . $this->Time->format('H:i', $wave['PizzaWave']['time_end']);
					?>
					<strong title="Currently selected wave">
						<?php echo $this->Html->link($content, array('action' => 'index', 'wave_id' => $wave['PizzaWave']['id']), array('escape' => false)); ?>
					</strong>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<?php if (isset($is_orderable) && $is_orderable): ?>
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
					$this->Html->image('16x16_GIF/action_add.gif'). ' Submit order', array(
				'controller' => 'pizza_orders',
				'action' => 'add'
					), array(
				'class' => 'pizza_order_submit',
						'escape' => false
					)
			);
			?>
			<div class="hidden"><?php echo $current_wave['PizzaWave']['id']; ?></div>
			<div style="clear:both;"></div>
		</div>
		<div class="pizza_order_sending hidden"></div>
		<div class="pizza_order_success hidden">Pizza order submitted</div>
		<div class="pizza_order_errors hidden"></div>
	</div>
<?php endif; ?>