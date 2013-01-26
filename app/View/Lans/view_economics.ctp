<div>
	<table>
		<thead>
			<tr>
				<th style="width: 16px;"></th>
				<th>Post</th>
				<th style="text-align: center">Quantity</th>
				<th style="text-align: center" colspan="2">Price</th>
				<th style="text-align: center" colspan="2">Total</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="text-align:center;"><i class="icon-user icon-large"></i></td>
				<td>User signups</td>
				<td style="text-align: center"><?php echo $count_lan_signups; ?></td>

				<td style="text-align: right"><?php echo $lan['Lan']['price']; ?></td>
				<td>DKK</td>

				<td style="text-align: right"><?php echo $count_lan_signups * $lan['Lan']['price']; ?></td>
				<td>DKK</td>

			</tr>
			<tr>
				<td style="text-align:center;"><i class="icon-coffee icon-large"></i></td>
				<td>Candy &amp; soda</td>
				<td style="text-align: center"><?php echo $count_food_orders; ?></td>

				<td style="text-align: right">~ <?php echo $count_food_orders > 0 ? floor($money_food_orders / $count_food_orders) : 0; ?></td>
				<td>DKK</td>

				<td style="text-align: right"><?php echo $money_food_orders; ?></td>
				<td>DKK</td>

			</tr>
			<tr>
				<td style="text-align:center;"><i class="icon-food icon-large"></i></td>
				<td>Pizza orders</td>
				<td style="text-align: center"><?php echo $count_pizza_orders; ?></td>

				<td style="text-align: right">~ <?php echo $count_pizza_orders > 0 ? floor($money_pizza_orders / $count_pizza_orders) : 0; ?></td>
				<td>DKK</td>
				<td style="text-align: right"><?php echo $money_pizza_orders; ?></td>
				<td>DKK</td>

			</tr>
			<tr>
				<th colspan="2">Total</th>
				<th colspan="3"></th>

				<th style="text-align: right"><?php echo $money_total; ?></th>
				<th>DKK</th>
			</tr>
		</tbody>
	</table>
</div>