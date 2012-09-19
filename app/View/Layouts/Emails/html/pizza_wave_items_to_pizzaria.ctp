<?php $pizza_wave_items = $content_for_layout; ?>
<div>
	<h1>DTU LAN Party - Pizza bestilling</h1>
	<table>
		<thead>
			<tr>
				<th>Antal</th>
				<th>#</th>
				<th>Navn</th>
				<th>Type</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($pizza_wave_items as $item): ?>
				<tr>
					<td><?php echo $item['quantity']; ?></td>
					<td><?php echo $item['pizza_number']; ?></td>
					<td><?php echo $item['pizza_title']; ?></td>
					<td><?php echo $item['pizza_type']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>