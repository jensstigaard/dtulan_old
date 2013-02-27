<div class="box">
	<div style="float:right;">
		<?php echo $this->Html->link($this->Html->image('16x16_GIF/reply.gif') . ' Edit food menu', array('action' => 'edit', $food_menu['FoodMenu']['id']), array('escape' => false)); ?>
	</div>

	<h1><?php echo $food_menu['FoodMenu']['title']; ?></h1>
	<p><?php echo $food_menu['FoodMenu']['description']; ?></p>

</div>
<div class="box">
	<h2>Used in LANS</h2>
	<table>
		<thead>
			<tr>
				<th>LAN title</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($used_in_lans as $lan_food_menu): ?>
				<tr>
					<td><?php
			echo $this->Html->link($lan_food_menu['Lan']['title'], array(
				 'controller' => 'lans',
				 'action' => 'view',
				 'slug' => $lan_food_menu['Lan']['slug']
			));
			?></td>
					<td></td>
				</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div class="box">
	<div style="float:right">
	<?php echo $this->Html->link('Add category', array('controller' => 'food_categories', 'action' => 'add', $food_menu['FoodMenu']['id'])); ?>
	</div>
	<h1>Items</h1>
	<?php if (!count($categories)): ?>
		<p>No items</p>
		<?php else: ?>
		<table>
	<?php foreach ($categories as $category): ?>
				<thead>
					<tr>
						<th><?php echo $category['FoodCategory']['title']; ?></th>
						<th>Description</th>
						<th>Price</th>
						<th>Times sold</th>
						<th><?php echo $this->Html->link($this->Html->image('16x16_GIF/reply.gif'), array('controller' => 'food_categories', 'action' => 'edit', $category['FoodCategory']['id']), array('escape' => false, 'title' => 'Edit category')); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($category['Food'] as $food): ?>
						<?php
						$if_admin_and_unavailable = '';
						if (!$food['available']) {
							$if_admin_and_unavailable = ' gray';
						}
						?>
						<tr class="<?php echo $if_admin_and_unavailable; ?>">
							<td><?php echo $food['title']; ?></td>
							<td>
								<small><?php echo $food['description']; ?></small>
							</td>
							<td><?php echo $food['price']; ?> DKK</td>
							<td><?php echo $food['countTimesSold']; ?></td>
							<td>
								<?php echo $this->Html->link($this->Html->image('16x16_GIF/reply.gif'), array('controller' => 'foods', 'action' => 'edit', $food['id']), array('escape' => false, 'title' => 'Edit item')); ?>
								<?php
								if (!$food['countTimesSold']) {
									echo $this->Html->link($this->Html->image('16x16_GIF/action_delete.gif'), array('controller' => 'foods', 'action' => 'delete', $food['id']), array('escape' => false, 'title' => 'Delete item', 'confirm' => 'Are you sure?'));
								}
								?>
							</td>
						</tr>
							<?php endforeach; ?>
					<tr>
						<td colspan="5">
		<?php echo $this->Html->link('Add item', array('controller' => 'foods', 'action' => 'add', $category['FoodCategory']['id'])) ?>
						</td>
					</tr>
				</tbody>
		<?php endforeach; ?>
		</table>
<?php endif; ?>
</div>