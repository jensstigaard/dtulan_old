<div>
	<div style="float:right">
		<?php
		echo $this->Html->link($this->Html->image('16x16_PNG/add.png') . ' Add Food Menu to LAN', array(
			'controller' => 'lan_food_menus',
			'action' => 'add',
			$id
				), array(
			'escape' => false
		));
		?>
	</div>
	<?php if (!count($food_menus)): ?>
		<p>No Food Menus connected to this LAN</p>
	<?php else: ?>
		<table>
			<thead>
				<tr>
					<th>Title</th>
					<th>Orders made</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($food_menus as $food_menu): ?>
				<tr>
					<td><?php echo $this->Html->link($food_menu['FoodMenu']['title'], array('controller'=>'food_menus','action'=>'view',$food_menu['FoodMenu']['id'])); ?></td>
					<td><?php echo $food_menu['LanFoodMenu']['count_orders']; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>