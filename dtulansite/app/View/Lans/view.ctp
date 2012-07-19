<div class="form">
	<h2><?php echo $lan['Lan']['title']; ?></h2>
	<h3>General info</h3>
	<ul style="margin-bottom: 20px;">
		<li>Date start: <?php echo $lan['Lan']['time_start']; ?></li>
		<li>Date end: <?php echo $lan['Lan']['time_end']; ?></li>
		<li>Public: <?php echo $lan['Lan']['published'] ? 'Yes' : 'No'; ?></li>
		<li>Sign up open: <?php echo $lan['Lan']['sign_up_open'] ? 'Yes' : 'No'; ?></li>
	</ul>

	<h3>List of signups for this LAN</h3>
	<table>
		<tr>
			<th>Name</th>
			<th>Paid</th>
		</tr>
		<?php foreach ($lan['LanSignup'] as $signup): ?>
			<tr>
				<td><?php echo $signup['User']['name']; ?></td>
				<td></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<!--<pre>
	<?php // print_r($lan); ?>
	</pre>-->
</div>
