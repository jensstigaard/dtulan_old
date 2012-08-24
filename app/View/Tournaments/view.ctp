<div class="view">
	<h1><?php echo $tournament['Tournament']['title']; ?></h1>
	<ul>
		<li>Full team size: <?php echo $tournament['Tournament']['max_team_size']; ?></li>
		<li>Description:</li>
		<?php echo $tournament['Tournament']['description']; ?>
	</ul>
	<table>
		<tr>
			<th>Team Name</th>
			<th>Member count</th>
		</tr>
			<tr>
				<td><?php //Team name ?></td>
				<td><?php //member count ?></td>
			</tr>
	</table>
	
	<?php pr($tournament) ?>
</div>