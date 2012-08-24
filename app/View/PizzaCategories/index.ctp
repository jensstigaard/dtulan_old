<?php
echo $this->Html->css('pizzas', null, array('inline' => false));

if ($isOrderable) {
	echo $this->Html->script(array('jquery', 'pizzas_orderable'), FALSE);
}
?>

<div class="form">
	<div style="float:right;">
		<?php if ($logged_in && $is_admin): ?>
			<?php echo $this->Html->link('New pizza category', array('action' => 'add')); ?>
		<?php endif; ?>
	</div>

	<h2>Pizzas</h2>
	<p>You'll see the list of available pizzas below</p>
	<?php if ($logged_in): ?>
		<div class="pizza_order">
			<h1>Your order</h1>
			<?php echo $this->Form->create('Pizza_order'); ?>
			<table>
				<tr>
					<th colspan="3">Pizza</th>
					<th colspan="3">Price</th>
				</tr>
				<tr>
					<td colspan="6"></td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan="3">Total:</td>
					<td style="width:50px;" class="pizza_order_total">0</td>
					<td>DKK</td>
					<td></td>
				</tr>
			</table>
			<?php echo $this->Form->end('Submit order'); ?>
		</div>
	<?php endif; ?>
	<?php foreach ($pizza_categories as $pizza_category): ?>
		<table class="pizza_list">
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
					?></th>
				<?php endif; ?>
			</tr>
			<?php if (!count($pizza_category['Pizza'])): ?>
				<tr>
					<td colspan="7">
						No pizzas in this category
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($pizza_category['Pizza'] as $pizza): ?>
					<?php if ($pizza['available'] || $isAdmin): ?>
						<tr class="pizza_item">
							<td class="number"><?php echo $pizza['number']; ?></td>
							<td class="title"><?php echo $pizza['title']; ?></td>
							<td class="desc"><?php echo $pizza['description']; ?></td>
							<?php
							foreach ($pizza['Prices'] as $price_type_id => $price_info):
								?>
								<td class="price"><?php
					if ($price_info['price'] != 0) {
						echo'<span>' . $price_info['price'] . '</span>,- ';
						echo'<span class="hidden price_id">' . $price_info['id'] . '</span>';
					}
								?></td>
							<?php endforeach; ?>
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
			<?php endif; ?>
			<?php if ($is_admin): ?>
				<tr>
					<td colspan="7">
						<?php echo $this->Html->link('New pizza in this category', array('controller' => 'pizzas', 'action' => 'add', $pizza_category['PizzaCategory']['id'])); ?>
					</td>
				</tr>
			<?php endif; ?>
		</table>
	<?php endforeach; ?>
	<div style="clear:both;"></div>
	<?php pr($pizza_categories); ?>
</div>