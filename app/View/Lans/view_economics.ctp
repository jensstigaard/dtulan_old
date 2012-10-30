<div>
	<?php
	// Reset total
	$total_lan = 0;

	// For signups
	$total_lan += $count_lan_signups * $lan['Lan']['price'];

	// For pizzas
	$total_lan += $total_pizzas;
	?>
	<table>
		<thead>
			<tr>
				<th>Post</th>
				<th style="text-align: center">Quantity</th>
				<th></th>
				<th style="text-align: left">Price</th>
				<th></th>
				<th style="text-align: right">Total</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td>User signups</td>
				<td style="text-align: center"><?php echo $count_lan_signups; ?></td>
				<td style="text-align: right">DKK</td>
				<td style="text-align: right"><?php echo $lan['Lan']['price']; ?></td>
				<td style="text-align: right">DKK</td>
				<td style="text-align: right"><?php echo $count_lan_signups * $lan['Lan']['price']; ?></td>

			</tr>
			<tr>
				<td>Candy &amp; soda</td>
				<td style="text-align: center"><?php echo $food_orders_count; ?></td>
				<td style="text-align: right">DKK</td>
				<td style="text-align: right">~ <?php echo $food_orders_count > 0 ? floor($food_orders_total / $food_orders_count) : 0; ?></td>
				<td style="text-align: right">DKK</td>
				<td style="text-align: right"><?php echo $food_orders_total; ?></td>

			</tr>
			<tr>
				<td>Pizza orders</td>
				<td style="text-align: center"><?php echo $total_pizza_orders; ?></td>
				<td style="text-align: right">DKK</td>
				<td style="text-align: right">~ <?php echo $total_pizza_orders > 0 ? floor($total_pizzas / $total_pizza_orders) : 0; ?></td>
				<td style="text-align: right">DKK</td>
				<td style="text-align: right"><?php echo $total_pizzas; ?></td>

			</tr>
			<tr>
				<th>Total</th>
				<th colspan="3"></th>
				<th style="text-align: right">DKK</th>
				<th style="text-align: right"><?php echo $total_lan; ?></th>

			</tr>
		</tbody>
	</table>
</div>