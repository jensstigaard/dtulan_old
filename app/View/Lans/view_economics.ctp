<div>
	<table>
		<thead>
			<tr>
				<th style="width: 16px;"></th>
				<th>Post</th>
				<th style="text-align: center">Quantity</th>
				<th style="text-align: center" colspan="2">Price (avg.)</th>
				<th style="text-align: center" colspan="2">Total</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="text-align:center;"><i class="icon-user icon-large"></i></td>
				<td>User signups</td>
				<td style="text-align: center"><?php echo $data['participants']['count']; ?></td>

				<td style="text-align: right"><?php echo $data['lan']['price']; ?></td>
				<td>DKK</td>

				<td style="text-align: right"><?php echo $data['participants']['money']; ?></td>
				<td>DKK</td>

			</tr>
			<tr>
				<td style="text-align:center;"><i class="icon-food icon-large"></i></td>
				<td>Pizza orders</td>
				<td style="text-align: center"><?php echo $data['pizza_orders']['count']; ?></td>

				<td style="text-align: right">~<?php echo $data['pizza_orders']['average']; ?></td>
				<td>DKK</td>
				<td style="text-align: right"><?php echo $data['pizza_orders']['money']; ?></td>
				<td>DKK</td>
			</tr>
			<tr>
				<td style="text-align:center;"><i class="icon-coffee icon-large"></i></td>
				<td>Candy &amp; soda</td>
				<td style="text-align: center"><?php echo $data['food_orders']['count']; ?></td>

				<td style="text-align: right">~<?php echo $data['food_orders']['average']; ?></td>
				<td>DKK</td>

				<td style="text-align: right"><?php echo $data['food_orders']['money']; ?></td>
				<td>DKK</td>
			</tr>
			
			<tr>
				<th colspan="2">Total</th>
				<th colspan="3"></th>

				<th style="text-align: right"><?php echo $data['lan']['total']; ?></th>
				<th>DKK</th>
			</tr>
		</tbody>
	</table>
</div>