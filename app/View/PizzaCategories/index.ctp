<?php
echo $this->Html->css('pizzas', null, array('inline' => false));

if ($is_orderable) {
	echo $this->Html->script(array('jquery', 'pizzas_orderable'), FALSE);
}
?>

<div class="form">
	<div style="float:right;">
		<?php if ($logged_in && $is_admin): ?>
			<?php echo $this->Html->link('New pizza category', array('action' => 'add')); ?>
			| <?php echo $this->Html->link('New pizza type', array('controller' => 'pizza_types', 'action' => 'add')); ?>
			| <?php echo $this->Html->link('New pizza wave', array('controller' => 'pizza_waves', 'action' => 'add', $current_lan['Lan']['id'])); ?>
		<?php endif; ?>
	</div>

	<h2>Pizzas</h2>
	<p>You'll see the list of available pizzas below</p>
	<div class="right">
		<?php if ($logged_in && count($waves)): ?>
			<div class="pizza_waves">
				<h1>Pizza waves available</h1>
				<?php if ($current_wave == ''): ?>
					<div class="notice" style="font-size:11pt; padding:5px;">
						Choose a wave below to order pizzas.
					</div>
				<?php endif; ?>
				<?php
				$last_date = '';
				foreach ($waves as $wave):
					$this_date = $this->Time->format('Y-m-d', $wave['PizzaWave']['time_start']);
					?>
					<?php if ($last_date != $this_date): ?>
						<?php if ($last_date != '') : ?>
							<br />
							<hr />
						<?php endif; ?>
						<h3><?php echo $this->Time->format('D, M jS', $wave['PizzaWave']['time_start']); ?></h3>
						<?php $last_date = $this_date; ?>
					<?php endif; ?>
					<div>
						<?php
						$content = $this->Time->format('H:i', $wave['PizzaWave']['time_start']) . ' - ' . $this->Time->format('H:i', $wave['PizzaWave']['time_end']);
						if ($wave['PizzaWave']['id'] == $current_wave['PizzaWave']['id']) :
							?>
							<strong title="Currently selected wave"><?php echo $content; ?>
								<?php if ($is_admin): ?>
									<?php echo $this->Html->image('16x16_GIF/reply.gif', array('url' => array('controller' => 'pizza_waves', 'action' => 'edit', $wave['PizzaWave']['id']))); ?>
								<?php endif; ?>
							</strong>
						<?php else: ?>
							<?php echo $this->Html->link($content, array('action' => 'index', 'wave_id' => $wave['PizzaWave']['id'])); ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ($is_orderable): ?>
			<div class="pizza_order">
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
							<td colspan="6"></td>
						</tr>
						<tr>
							<td style="text-align:right;" colspan="3">Total:</td>
							<td style="width:50px;" class="pizza_order_total">0</td>
							<td>DKK</td>
							<td></td>
						</tr>
					</tbody>

				</table>
				<div>
					<?php echo $this->Js->link('Submit order', array('controller' => 'pizza_orders', 'action' => 'add'), array('class' => 'pizza_order_submit')); ?>
					<div class="hidden"><?php echo $current_wave['PizzaWave']['id']; ?></div>
				</div>
				<?php echo $this->Form->end(); ?>
				<div class="pizza_order_sending" style="display:none;"></div>
				<div class="pizza_order_success"></div>
			</div>
		<?php endif; ?>
	</div>
	<?php foreach ($pizza_categories as $pizza_category): ?>
		<?php if (count($pizza_category['Pizza']) || $is_admin): ?>
			<table class="pizza_list">
				<thead>
					<tr class="pizza_category">

						<th colspan="3"><?php echo $pizza_category['PizzaCategory']['title']; ?><br />
							<small><?php echo $pizza_category['PizzaCategory']['description'] ?></small>
						</th>
						<?php foreach ($pizza_category['PizzaType'] as $type): ?>
							<th><?php echo $type['title_short']; ?></th>
						<?php endforeach; ?>
						<?php if ($is_admin): ?>
							<th><?php
				echo $this->Html->image('16x16_GIF/reply.gif', array(
					'alt' => 'Edit category',
					'title' => 'Edit category',
					'url' => array(
						'controller' => 'pizza_categories',
						'action' => 'edit', $pizza_category['PizzaCategory']['id'])
						)
				);
				echo $this->Html->image('16x16_GIF/action_add.gif', array(
					'alt' => 'Add pizza to category',
					'title' => 'Add pizza to category',
					'url' => array(
						'controller' => 'pizzas',
						'action' => 'add', $pizza_category['PizzaCategory']['id'])
						)
				);
							?>
							</th>
						<?php endif; ?>
					</tr>
				</thead>
				<?php if (!count($pizza_category['Pizza'])): ?>
					<tr>
						<td colspan="7">
							<?php echo $this->Html->image('24x24_PNG/001_11.png', array('style' => 'vertical-align:middle')); ?>
							<strong>
								No pizzas in this category.
							</strong>
							<small>(Not visible to guests)</small>

						</td>
					</tr>
				<?php else: ?>
					<tbody>
						<?php
						foreach ($pizza_category['Pizza'] as $pizza):
							$if_admin_and_unavailable = '';

							if ($pizza['available'] || $is_admin):
								if (!$pizza['available']) {
									$if_admin_and_unavailable = ' gray';
								}
								?>
								<tr class="pizza_item<?php echo $if_admin_and_unavailable; ?>">
									<td class="number"><?php echo $pizza['number']; ?></td>
									<td class="title"><?php echo $pizza['title']; ?></td>
									<td class="desc"><?php echo $pizza['description']; ?></td>
									<?php
									if (isset($pizza['Prices'])) {
										foreach ($pizza['Prices'] as $price_type_id => $price_info):
											?>
											<td class="price"><?php
							if ($price_info['price'] != 0) {
								echo'<span>' . $price_info['price'] . '</span>,- ';
								echo'<span class="hidden price_id">' . $price_info['id'] . '</span>';
							}
											?></td>
											<?php
										endforeach;
									}
									?>
									<?php if ($is_admin): ?>
										<td><?php
						echo $this->Html->image('16x16_GIF/reply.gif', array(
							'alt' => 'Edit pizza',
							'title' => 'Edit pizza',
							'url' => array('controller' => 'pizzas', 'action' => 'edit', $pizza['id'])));
										?></td>
									<?php endif; ?>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
				<?php endif; ?>
			</table>
		<?php endif; ?>
	<?php endforeach; ?>
	<div style="clear:both;"></div>
	<div class="hidden_images">
		<?php
		echo $this->Html->image('16x16_GIF/action_add.gif', array('class' => 'image_add'));
		echo $this->Html->image('16x16_GIF/action_remove.gif', array('class' => 'image_remove'));
		?>
	</div>

</div>