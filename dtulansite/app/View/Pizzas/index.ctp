<h2>Pizzas</h2>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Pizza category id</th>
        <th>Number</th>
        <th>Description</th>
		<th>Available</th>
    </tr>


	<!-- Here's where we loop through our $posts array, printing out post info -->

	<?php foreach ($pizzas as $pizza): ?>
		<tr>
			<td><?php echo $pizza['Pizza']['id']; ?></td>
			<td><?php echo $this->Html->link($pizza['Pizza']['title'], array('action' => 'edit', $pizza['Pizza']['id']));
		?></td>
			<td><?php echo $pizza['Pizza']['pizza_category_id'] ?></td>
			<td><?php echo $pizza['Pizza']['number'] ?></td>
			<td><?php echo $pizza['Pizza']['description'] ?></td>
			<td><?php echo $pizza['Pizza']['available'] ?></td>
		</tr>
	<?php endforeach; ?>

</table>