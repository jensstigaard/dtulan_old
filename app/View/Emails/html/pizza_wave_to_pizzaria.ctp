<div>
	<h1>DTU LAN Party - Pizza bestilling</h1>
	<table style="width:100%" cellspacing="0" cellpadding="5">
		<thead>
			<tr>
				<th>Antal</th>
				<th>#</th>
				<th>Navn</th>
				<th>Type</th>
				<th>Færdig</th>
			</tr>
		</thead>

		<tbody>
			<?php 
			foreach ($pizza_wave_items as $item) {
				echo '<tr>';
				echo '<td>' . $item['quantity'] . '</td>';
				echo '<td>' . $item['pizza_number'] . '</td>';
				echo '<td>' . $item['pizza_title'] . '</td>';
				echo '<td>' . $item['pizza_type'] . '</td>';
				echo '<td>&nbsp; </td>';
				echo '</tr>';
			} 
			?>
		</tbody>
	</table>
</div>