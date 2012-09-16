<?php
echo $this->Html->css('pizzas', null, array('inline' => false));

if ($is_orderable) {
	echo $this->Html->script(array('jquery', 'pizzas_orderable'), FALSE);
}
?>

<div class="form">
	<div style="float:right;">
		<?php if ($is_admin): ?>
			<?php echo $this->Html->link('New pizza category', array('action' => 'add')); ?>
			| <?php echo $this->Html->link('New pizza type', array('controller' => 'pizza_types', 'action' => 'add')); ?>
			<?php if (isset($current_lan)): ?>
				| <?php echo $this->Html->link('New pizza wave', array('controller' => 'pizza_waves', 'action' => 'add', $current_lan['Lan']['id'])); ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<h1>Pizzas</h1>
	<p>You'll see the list of available pizzas below</p>
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
											<td class="price">
												<?php if ($price_info['price'] != 0): ?>
													<span<?php echo $pizza['available'] ? ' class="available"' : '' ?>><?php echo $price_info['price']; ?></span>,-
													<span class="hidden price_id"><?php echo $price_info['id']; ?></span>
												<?php endif; ?>
											</td>
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
	<?php // pr($pizza_categories); ?>
</div>