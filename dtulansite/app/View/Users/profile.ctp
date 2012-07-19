<div class="form">
	<h2><?php echo $user['User']['name']; ?></h2>
	<h3>General data</h3>

	<?php if ($current_user['id'] == $user['User']['id']): ?>
		<div style="float:right;">Balance: <?php echo @$user['User']['balance']; ?></div>
	<?php endif; ?>

	<div>
		<ul style="margin-bottom: 20px;">
			<li>Email: <?php echo $user['User']['email']; ?></li>
			<li>Gamertag: <?php echo $user['User']['gamertag']; ?></li>
			<li>Type: <?php echo $user['User']['type']; ?></li>
			<li>ID-number: <?php echo $user['User']['id_number']; ?></li>
		</ul>
	</div>


	<div>
		<h3>LANs:</h3>
		<table>
			<th>Title</th>
			<?php foreach ($user['LanSignup'] as $signup): ?>
				<tr>
					<td><?php echo $signup['Lan']['title']; ?></td>
		<!--				<td></td>-->
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<pre>
		<?php
		print_r($user);
		?>
	</pre>
</div>