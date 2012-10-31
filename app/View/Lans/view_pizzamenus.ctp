<div>
	<?php if(!count($pizza_menus)): ?>
	<p>No pizza menus connected yet.</p>
	<?php else: ?>
	<table>
		<thead>
			<tr>
				<th>Title</th>
				<th>Waves</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($pizza_menus as $pizza_menu): ?>
			<tr>
				<td><?php echo $this->Html->link($pizza_menu['PizzaMenu']['title'], array('action'=>'view_pizzamenu', $pizza_menu['PizzaMenu']['id'])); ?></td>
				<td><?php echo count($pizza_menu['PizzaWave']); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>