<div class="form">
	<h1>Profile</h1>
	<?php if ($user['User']['id'] == $current_user['id']): ?>
		<div style="float:right">
			<?php echo $this->Html->link('Edit personal data', array('action' => 'editPersonalData')); ?>
		</div>
	<?php endif; ?>
	<h2><?php echo $user['User']['name']; ?></h2>

	<?php if ($user['User']['activated'] != 1 && $is_admin): ?>
		<div class="message">
			<p style="margin:0;">
				This user is not activated!
			</p>
		</div>
	<?php endif; ?>

	<?php if ($user['User']['id'] == $current_user['id'] && $next_lan): ?>
		<div class="message">
			<p style="margin:0;">You are not signed up for <?php echo $next_lan['Lan']['title']; ?>! Sign up now!</p>
		</div>
	<?php endif; ?>


	<h3>User info</h3>

	<?php if ($current_user['id'] == $user['User']['id'] || $is_admin): ?>
		<div style="float:right;">Balance: <?php echo @$user['User']['balance']; ?></div>
	<?php endif; ?>



	<div>
		<ul style="margin: 0 0 20px;list-style: none;">
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
			<?php foreach ($user['Lan'] as $lan): ?>
				<tr>
					<td><?php echo $lan['title']; ?></td>
		<!--				<td></td>-->
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<?php pr($user); ?>
	<?php // pr($next_lan); ?>
</div>