<?php echo $this->Html->css(array('tournament_page'), null, array('inline' => false)); ?>

<div>
	<div id="tournament-info" style="background-image: url('<?php echo $this->Html->url('/img/uploads/' . $tournament['Game']['Image']['id'] . '.' . $tournament['Game']['Image']['ext']); ?>')">

		<table>
			<tbody>
				<tr>
					<td>
						<span class="badge badge-inverse">
							<?php echo $tournament['Tournament']['time_start_nice']; ?>
						</span>
					</td>

					<td>
						<span class="badge badge-inverse">
							<?php echo $tournament['Tournament']['team_size']; ?> vs <?php echo $tournament['Tournament']['team_size']; ?>
						</span>
					</td>

					<td>
						<span class="badge <?php echo count($tournament['Team']) ? 'badge-inverse' : 'badge-important'; ?>">
							<?php echo count($tournament['Team']); ?> teams
						</span>
					</td>

					<td>
						<span class="badge badge-info">
							<?php
							echo $this->Html->link('LAN: ' . $tournament['Lan']['title'], array(
								 'controller' => 'lans',
								 'action' => 'view',
								 'slug' => $tournament['Lan']['slug']
									  ), array(
								 'style' => 'color:white;'
									  )
							);
							?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php
			echo $this->Html->link('<i class="icon-large icon-pencil"></i> Edit', array(
				 'action' => 'edit',
				 $tournament['Tournament']['id']
					  ), array(
				 'escape' => false,
				 'class' => 'btn btn-small btn-inverse'
			));
			?>
		</div>
	<?php endif; ?>

	<h1 style="margin-top:0;text-align: center;"><?php echo $tournament['Tournament']['title']; ?></h1>

</div>

<?php if (count($winner_teams)) : ?>
	<div>
		<h2>Tournament-winners</h2>
		<div class="floated-list" id="tournament-winners">
			<?php foreach ($winner_teams as $team): ?>
				<div class="item" style="text-align: left; padding: 5px 10px 5px 5px;width:30%">
					<?php
					if ($team['TournamentWinner']['place'] == 1) {
						$image = 'gold';
					} elseif ($team['TournamentWinner']['place'] == 2) {
						$image = 'silver';
					} elseif ($team['TournamentWinner']['place'] == 3) {
						$image = 'bronze';
					}
					?> 
					<?php
					echo $this->Html->image('48x48_PNG/trophy_' . $image . '.png', array(
						 'style' => 'display:block;margin: 10px auto;'
					));
					?>
					<div style="text-align: center;">
						<h5><?php echo $team['TournamentWinner']['place']; ?>. place</h5>
						<h4><?php echo $team['Team']['name']; ?></h4>
					</div>
					<div class="floated-list" style="margin:5px 2px;">
						<?php foreach ($team['TeamUser'] as $user): ?>
							<?php
							echo $this->Html->image(
									  'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=35&amp;r=r', array(
								 'url' => array(
									  'controller' => 'users',
									  'action' => 'view',
									  $user['User']['id']
								 ),
								 'title' => $user['User']['name'],
								 'class' => 'item person',
								 'data-placement' => 'bottom',
								 'data-animation' => false
									  )
							);
							?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>


<div>
	<div class="tabs">
		<ul>
			<li>
				<?php
				echo $this->Html->link('<i class="icon-info-sign"></i>', array(
					 'action' => 'view_description',
					 $tournament['Lan']['slug'],
					 $tournament['Tournament']['slug']
						  ), array(
					 'escape' => false
				));
				?>
			</li>
			<li>
				<?php
				echo $this->Html->link('<i class="icon-book"></i>', array(
					 'action' => 'view_rules',
					 $tournament['Lan']['slug'],
					 $tournament['Tournament']['slug']
						  ), array(
					 'escape' => false
				));
				?>
			</li>
			<li>
				<?php
				echo $this->Html->link('<i class="icon-group"></i></a>', array(
					 'action' => 'view_teams',
					 $tournament['Lan']['slug'],
					 $tournament['Tournament']['slug']
						  ), array(
					 'escape' => false
				));
				?>
			</li>
			<li>
				<?php
				echo $this->Html->link('<i class="icon-sitemap"></i>', array(
					 'action' => 'view_bracket',
					 $tournament['Lan']['slug'],
					 $tournament['Tournament']['slug']
						  ), array(
					 'escape' => false
				));
				?>
			</li>
			<?php if ($can_create_team): ?>
				<li>
					<?php
					echo $this->Html->link('<i class="icon-plus-sign"></i>', array(
						 'controller' => 'teams',
						 'action' => 'add',
						 $tournament['Lan']['slug'],
						 $tournament['Tournament']['slug'],
							  ), array(
						 'escape' => false
					));
					?>
				</li>

			<?php endif; ?>
		</ul>

		<div class="loading_indicator">
			<i class="icon-spinner icon-spin icon-2x"></i>
		</div>
	</div>
</div>