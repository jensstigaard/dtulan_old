<div>
	<?php if (!count($teams)): ?>
		<p>You do not participate in any tournament</p>
	<?php else: ?>
		<?php $lan_slug_current = ''; ?>
		<?php foreach ($teams as $team): ?>
			<?php
			if ($lan_slug_current != $team['Team']['Tournament']['Lan']['slug']):
				if ($lan_slug_current != ''):
					?>
				</div>
			<?php endif; ?>
			<h1><?php echo $team['Team']['Tournament']['Lan']['title']; ?></h1>
			<div class="floated-list teams-list">
				<?php
				$lan_slug_current = $team['Team']['Tournament']['Lan']['slug'];
			endif;
			?>

			<a class="item" href="<?php
		echo $this->Html->url(array(
			 'controller' => 'tournaments',
			 'action' => 'view',
			 'lan_slug' => $team['Team']['Tournament']['Lan']['slug'],
			 'tournament_slug' => $team['Team']['Tournament']['slug']
		));
			?>" style="background-image:url('<?php echo $this->Html->url('../img/uploads/' . $team['Team']['Tournament']['Game']['Image']['thumbPath']); ?>');">
				<strong class="bottom">
					<?php // echo $team['Team']['Tournament']['title']; ?>
					<!--&bull;-->
					<?php
//					if (!strlen($team['Team']['name']) > 10) {
						echo $team['Team']['name'];
//					} else {
//						echo substr($team['Team']['name'], 0, 10);
//					}
					?>
					<?php if(isset($team['Team']['TournamentWinner']['place'])):
						switch($team['Team']['TournamentWinner']['place']){
						case 1: 
							$color = 'gold';
							break;
						case 2: 
							$color = 'silver';
							break;
						default:
							$color = 'peru';
							break;
						} ?>
					<i class="icon-large icon-trophy" style="color: <?php echo $color; ?>"></i>
					<?php endif; ?>
				</strong>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
</div>
<?php // pr($teams); ?>