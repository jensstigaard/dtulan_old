<div>
	<?php if (!count($food_menus)): ?>
	
		<p style="margin:10px;font-size: 14pt;"><i class="icon-exclamation-sign" style="font-size:16pt;"></i> No food menus connected</p>
		
	<?php else: ?>
		
		<table>
			<thead>
				<tr>
					<th>Title</th>
					<th>Orders made</th>
					<th>Unhandled orders</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($food_menus as $food_menu): ?>
					<tr>
						<td><?php echo $this->Html->link($food_menu['FoodMenu']['title'], array('controller' => 'lan_food_menus', 'action' => 'view', $food_menu['FoodMenu']['id'])); ?></td>
						<td><?php echo $this->Html->link($food_menu['LanFoodMenu']['count_orders'], array('controller' => 'lan_food_menus', 'action' => 'view_orders', $food_menu['LanFoodMenu']['id'])); ?></td>
						<td>
							<?php echo $food_menu['LanFoodMenu']['count_orders_unhandled']; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>