<div>
	<h1>Food Menus</h1>

	<?php if(!count($food_menus)): ?>
	<p>No Food Menus yet</p>
	<?php else: ?>
	<table>
		<thead>
			<tr>
				<th>Title</th>
				<th>Categories</th>
				<th>Items</th>
				<th>Used in LANS</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($food_menus as $food_menu): ?>
			<tr>
				<td><?php echo $this->Html->link($food_menu['FoodMenu']['title'], array('action'=>'view', $food_menu['FoodMenu']['id'])); ?></td>
				<td><?php echo $food_menu['FoodMenu']['count_categories']; ?></td>
				<td><?php echo $food_menu['FoodMenu']['count_items']; ?></td>
				<td><?php echo $food_menu['FoodMenu']['count_used']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>