<div>
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('Edit tournament', array('action' => 'edit', $tournament['Tournament']['id'])); ?>
		</div>
	<?php endif; ?>

	<h1><?php echo $tournament['Tournament']['title']; ?></h1>
	<p>
		In LAN: <?php
	echo $this->Html->link($tournament['Lan']['title'], array(
		 'controller' => 'lans',
		 'action' => 'view', $tournament['Lan']['slug']
			  )
	);
	?>
	</p>
	<table>
		<tbody>
			<tr>
				<td>Team size:</td>
				<td><?php echo $tournament['Tournament']['team_size']; ?> persons</td>
			</tr>
			<tr>
				<td>Start time:</td>
				<td>
					<?php echo $tournament['Tournament']['time_start_nice']; ?>

				</td>
			</tr>
		</tbody>
	</table>
</div>

<?php if (count($winner_teams)) : ?>
	<div>
		<h2>Tournament-winners</h2>
		<div class="floated-list">
			<?php foreach ($winner_teams as $team): ?>
				<div class="item" style="text-align: left;padding-right:10px;">
					<h4><?php
		if ($team['TournamentWinner']['place'] == 1) {
			echo $this->Html->image('48x48_PNG/trophy_gold.png');
		} elseif ($team['TournamentWinner']['place'] == 2) {
			echo $this->Html->image('48x48_PNG/trophy_silver.png');
		} elseif ($team['TournamentWinner']['place'] == 3) {
			echo $this->Html->image('48x48_PNG/trophy_bronze.png');
		}
				?> 
						<?php echo $team['Team']['name']; ?></h4>
					<ul style="margin-left:50px;">
						<?php foreach ($team['TeamUser'] as $user): ?>
							<li><?php
				echo $this->Html->link($user['User']['name'], array(
					 'controller' => 'users',
					 'action' => 'profile',
					 $user['User']['id']
				));
							?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<?php // pr($winner_teams); ?>


<div>
	<div class="tabs">
		<ul>
			<li><a href="<?php
echo $this->Html->url(array(
	 'action' => 'view_description',
	 $tournament['Lan']['slug'],
	 $tournament['Tournament']['slug']
));
?>"><i class="icon-info-sign"></i></a></li>
			<li><a href="<?php
					 echo $this->Html->url(array(
						  'action' => 'view_rules',
						  $tournament['Lan']['slug'],
						  $tournament['Tournament']['slug']
					 ));
?>"><i class="icon-book"></i></a></li>
			<li><a href="<?php
					 echo $this->Html->url(array(
						  'action' => 'view_teams',
						  $tournament['Lan']['slug'],
						  $tournament['Tournament']['slug']
					 ));
?>"><i class="icon-group"></i></a></li>
			<li><a href="<?php
					 echo $this->Html->url(array(
						  'action' => 'view_bracket',
						  $tournament['Lan']['slug'],
						  $tournament['Tournament']['slug']
					 ));
?>"><i class="icon-sitemap"></i></a></li>
		</ul>

		<div class="loading_indicator">
			<i class="icon-spinner icon-spin icon-2x"></i>
		</div>
	</div>
</div>