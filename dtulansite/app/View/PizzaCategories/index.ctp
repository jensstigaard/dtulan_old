<h2>Pizzas</h2>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Description</th>
		<th>Sorted</th>
		<th>Available</th>
    </tr>


	<!-- Here's where we loop through our $posts array, printing out post info -->

	<?php foreach ($pizza_categories as $pizza_category): ?>
		<tr>
			<td><?php echo $pizza_category['PizzaCategory']['id']; ?></td>
			<td><?php echo $this->Html->link($pizza_category['PizzaCategory']['title'], array('action' => 'edit', $pizza_category['PizzaCategory']['id']));
		?></td>
			<td><?php echo $pizza_category['PizzaCategory']['sorted'] ?></td>
			<td><?php echo $pizza_category['PizzaCategory']['description'] ?></td>
			<td><?php echo $pizza_category['PizzaCategory']['available'] ?></td>
		</tr>

	<?php endforeach; ?>

</table>

<pre>
	<?php
	print_r($pizza_categories);
	?>
</pre>