<div>
	<h1>DTU LAN Party - Pizza bestilling</h1>
	<p>Adresse:</p>
	<p>
		Oticon salen<br />
		Anker Engelundsvej 1 (101 E)<br />
		2800 Kgs. Lyngby
	</p>
	<table style="width:100%;border: 1px solid #999;" cellspacing="0" cellpadding="5">
		<thead>
			<tr>
				<th>Antal</th>
				<th style="border-left: 1px solid #999;">Nr.</th>
				<th style="border-left: 1px solid #999;">Kategori</th>
				<th style="border-left: 1px solid #999;">Navn</th>
				<th style="border-left: 1px solid #999;">Type</th>
				<th style="border-left: 1px solid #999;">Færdig</th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($pizza_wave_items as $item) {
				echo '<tr>';
				echo '<td style="border-top: 1px solid #999;">' . $item['quantity'] . '</td>';
				echo '<td style="border-top: 1px solid #999;border-left: 1px solid #999;"">' . $item['pizza_number'] . '</td>';
				echo '<td style="border-top: 1px solid #999;border-left: 1px solid #999;"">' . $item['pizza_category'] . '</td>';
				echo '<td style="border-top: 1px solid #999;border-left: 1px solid #999;"">' . $item['pizza_title'] . '</td>';
				echo '<td style="border-top: 1px solid #999;border-left: 1px solid #999;"">' . $item['pizza_type'] . '</td>';
				echo '<td style="border: 1px solid #999;">&nbsp; </td>';
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
</div>