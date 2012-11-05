<div id="pizza_waves">
	<h1 style="padding:0;">Pizza Waves available:</h1>
	<?php if ($pizza_wave == ''): ?>
		<div class="notice" style="font-size:11pt; padding:5px;">
			Choose a wave below to order pizzas.
		</div>
	<?php endif; ?>
</div>
<?php
$last_date = '';
foreach ($pizza_waves as $wave):
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

				if ($wave['PizzaWave']['id'] == $pizza_wave['PizzaWave']['id']) {
					$content .= $this->Html->image('16x16_GIF/action_check.gif') . ' ';
				}

				$content.= $this->Time->format('H:i', $wave['PizzaWave']['time_end']);

				echo $this->Html->link($content, array('action' => 'view', $lan_pizza_menu_id, $wave['PizzaWave']['id']), array('escape' => false));
				?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>