<div style="padding: 5px;">
	<?php if (!count($teams)): ?>
		<p>No teams participating in this tournament yet</p>
	<?php else: ?>

		<div class="accordion" id="teams-list">
			<?php foreach ($teams as $team): ?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#teams-list" href="#collapse<?php echo $team['Team']['id']; ?>">
							<?php
							if ($team['TournamentWinner']['place'] == 1) {
								echo $this->Html->image('32x32_PNG/trophy_gold.png');
							} elseif ($team['TournamentWinner']['place'] == 2) {
								echo $this->Html->image('32x32_PNG/trophy_silver.png');
							} elseif ($team['TournamentWinner']['place'] == 3) {
								echo $this->Html->image('32x32_PNG/trophy_bronze.png');
							}
							?>

							<?php echo $team['Team']['name']; ?>

							<?php if (isset($team['Team']['is_part_of'])): ?>
								<span style="float:right;">
									<i class="icon-large icon-user"></i>
									Your team
								</span>
							<?php endif; ?>
						</a>
					</div>
					<div id="collapse<?php echo $team['Team']['id']; ?>" class="accordion-body collapse">
						<div class="accordion-inner">
							<?php if ($is_admin): ?>
								<?php if ($team['TournamentWinner']['place'] > 0): ?>
									<?php
									echo $this->Html->link('<i class="icon-large icon-remove"></i> Remove as winner', array(
										 'controller' => 'tournament_winners',
										 'action' => 'delete',
										 $team['TournamentWinner']['id']
											  ), array(
										 'escape' => false,
										 'confirm' => 'Are you sure?',
										 'class' => 'btn btn-danger pull-right'
									));
									?>
								<?php else: ?>
									<?php if (count($places_not_taken)): ?>
										<div class="btn-group pull-right">
											<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">
												Tournament-winner
												<span class="caret"></span>
											</a>
											<ul class="dropdown-menu">
												<?php foreach ($places_not_taken as $place): ?>
													<li><?php
							echo $this->Html->link($place . ' place', array(
								 'controller' => 'tournament_winners',
								 'action' => 'add',
								 $team['Team']['id'],
								 $place
							));
													?></li>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endif; ?>
								<?php endif; ?>

							<?php endif; ?>
							<table>
								<?php foreach ($team['TeamUser'] as $user): ?>
									<tr>
										<td>
											<?php
											echo $this->Html->image('http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=32&amp;r=r', array(
												 'style' => 'width:32px; height:32px;'
											));
											?>
										</td>
										<td>
											<?php echo $user['User']['gamertag']; ?>
										</td>
										<td>
											<?php
											echo $this->Html->link($user['User']['name'], array(
												 'controller' => 'users',
												 'action' => 'view',
												 $user['User']['id']
											));
											?>
										</td>
										<td>
											<?php if ($user['is_leader']): ?>
												<i class="icon-large icon-star" style="color:gold;" title="Teamleader"></i>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</table>
							<?php
							echo $this->Html->link('View team-details', array(
								 'controller' => 'teams',
								 'action' => 'view',
								 $team['Team']['id']
									  ), array(
								 'class' => 'btn btn-primary'
							));
							?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?php
// pr($teams); ?>