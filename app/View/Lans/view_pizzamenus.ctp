<div>
	<div style="float:right">
		<?php
		echo $this->Html->link($this->Html->image('16x16_PNG/add.png') . ' Add pizza-menu to LAN', array(
			'controller' => 'lan_pizza_menus',
			'action' => 'add',
			$id
				), array(
			'escape' => false
		));
		?>
	</div>
	<?php if (!count($pizza_menus)): ?>
	<p>No pizza menus connected to this LAN</p>
	<?php else: ?>
		<?php foreach ($pizza_menus as $pizza_menu): ?>
			<div>
				<div>
					<h1><?php echo $pizza_menu['PizzaMenu']['title']; ?></h1>
					<ul>
						<li>Orders made: coming</li>
					</ul>
				</div>

				<div style="float:right">
					<?php
					echo $this->Html->link($this->Html->image('16x16_PNG/add.png') . ' Add pizza-wave', array(
						'controller' => 'pizza_waves',
						'action' => 'add',
						$pizza_menu['LanPizzaMenu']['id']
							), array(
						'escape' => false
					));
					?>
				</div>
					<?php if (!count($pizza_menu['PizzaWave'])): ?>
					<p>No pizza waves found</p>
				<?php else: ?>
					<table>
						<tr>
							<th>
								<small>Date</small>
							</th>
							<th>
								<small>Time</small>
							</th>
							<th>
								<small>Status</small>
							</th>
							<th>Total</th>
							<th>Actions</th>
						</tr>
			<?php foreach ($pizza_menu['PizzaWave'] as $pizza_wave): ?>
							<tr>
								<td><?php echo $pizza_wave['time_start_nice']; ?></td>
								<td>
				<?php echo $this->Time->format('H:i', $pizza_wave['time_start']); ?>
									-
									<?php echo $this->Time->format('H:i', $pizza_wave['time_end']); ?>
								</td>
								<td>
				<?php
				switch ($pizza_wave['status']) {
					case 0:
						echo'Not open';
						break;
					case 1:
						echo'Open';
						break;
					case 2:
						echo'Waiting for delivering';
						break;
					case 3:
						echo'Pizza wave received';
						break;
					case 4:
						echo $this->Html->image('16x16_GIF/action_check.gif') . ' Finished';
						break;
					default:
						echo'Not proceded';
						break;
				}
				?>
								</td>
								<td>
				<?php echo $pizza_wave['pizza_orders_total']; ?> DKK
								</td>
								<td>
				<?php
				echo $this->Html->link(
						'View', array(
							'controller' => 'pizza_waves',
					'action' => 'view',
					$pizza_wave['id']
						)
				);
				?>
								</td>
							</tr>
			<?php endforeach; ?>
					</table>
					<?php endif; ?>
			</div>
			<hr />
	<?php endforeach; ?>
	<?php endif; ?>
</div>