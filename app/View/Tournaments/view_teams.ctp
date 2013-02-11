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
								echo $this->Html->image('32x32_PNG/trophy_gold.png');
							} elseif ($team['TournamentWinner']['place'] == 3) {
								echo $this->Html->image('32x32_PNG/trophy_gold.png');
							}
							?>

							<?php echo $team['Team']['name']; ?>

							<?php if (isset($team['Team']['is_part_of'])): ?>
								<i class="icon-large icon-map-marker" style="float:right;"></i>
							<?php endif; ?>
						</a>
					</div>
					<div id="collapse<?php echo $team['Team']['id']; ?>" class="accordion-body collapse">
						<div class="accordion-inner">
							<?php
							echo $this->Html->link('View team-details', array(
								 'controller' => 'teams',
								 'action' => 'view',
								 $team['Team']['id']
							));
							?>
							<table>
								<?php foreach ($team['TeamUser'] as $user): ?>
									<tr>
										<td>
											<?php
											echo $this->Html->image('http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=32&amp;r=r', array(
												 'style' => 'width:24px; height:24px;'
											));
											?>
										</td>
										<td>
											<?php echo $user['User']['gamertag']; ?>
										</td>
										<td>
											<?php echo $user['User']['name']; ?>
										</td>
										<td>
											<?php if ($user['is_leader']): ?>
												<i class="icon-large icon-star" style="color:gold;"></i>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</table>

						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?php
// pr($teams); ?>