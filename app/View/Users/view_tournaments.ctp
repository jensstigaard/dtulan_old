<div>
	<?php if (!count($teams)): ?>
		<p>You do not participate in any tournament</p>
	<?php else: ?>
		<div class="floated-list" id="teams-list">
			<?php foreach ($teams as $team): ?>
				<a class="item" href="<?php
		echo $this->Html->url(array(
			 'controller' => 'tournaments',
			 'action' => 'view',
			 $team['Team']['Tournament']['Lan']['slug'],
			 $team['Team']['Tournament']['slug']));
				?>" style="background-image:url('<?php echo $this->Html->url('../img/uploads/thumb_210w_' . $team['Team']['Tournament']['Game']['Image']['filePath']); ?>');">
					<strong class="bottom">
						<?php
						if ($team['Team']['Tournament']['team_size'] > 1) {
							echo $team['Team']['Tournament']['team_size'] . 'v' . $team['Team']['Tournament']['team_size'] . ' &bull; ';
						}
						?>
						<?php echo $team['Team']['Tournament']['Lan']['title']; ?>
						&bull;
						<?php
						if (!strlen($team['Team']['name']) > 10) {
							echo $team['Team']['name'];
						} else {
							echo substr($team['Team']['name'], 0, 10);
						}
						?>
						<?php echo $team['TeamUser']['is_leader'] ? $this->Html->image('16x16_PNG/star.png') : ''; ?>
					</strong>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>