<div>
	<div style="float:right">
		<?php echo $this->Html->link('Add Pizza Menu', array('action' => 'add')); ?>
	</div>
	<h1>Pizza menus</h1>

	<?php if (!count($pizza_menus)): ?>
		<p>No pizza menus are found :-(</p>
	<?php else: ?>
		<table>
			<thead>
				<tr>
					<th>Title</th>
					<th>Categories</th>
					<th>Items</th>
					<th>Used in LANs</th>
				</tr>
			</thead>
			<?php foreach ($pizza_menus as $pizza_menu): ?>
				<tr>
					<td><?php echo $this->Html->link($pizza_menu['PizzaMenu']['title'], array('action' => 'view', $pizza_menu['PizzaMenu']['id'])); ?></td>
					<td><?php echo $pizza_menu['PizzaMenu']['count_categories']; ?></td>
					<td><?php echo $pizza_menu['PizzaMenu']['count_items']; ?></td>
					<td><?php echo $pizza_menu['PizzaMenu']['count_used']; ?></td>

				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
<?php
// pr($pizza_menus); ?>