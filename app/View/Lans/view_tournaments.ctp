<div>

	<?php if (!count($tournaments)): ?>
		<p style="margin:10px;font-size: 14pt;"><i class="icon-exclamation-sign" style="font-size:16pt;"></i> No tournaments published yet</p>
	<?php else: ?>

		<div class="floated-list" id="list-tournaments">
			<?php foreach ($tournaments as $tournament): ?>
				<a href="<?php
		echo $this->Html->url(array(
			 'controller' => 'tournaments',
			 'action' => 'view',
			 'lan_slug' => $lan['Lan']['slug'],
			 'tournament_slug' => $tournament['Tournament']['slug']
		));
				?>" class="item" style="background-image:url('../../<?php echo $tournament['Game']['Image']['filePath']; ?>');">
					<strong class="bottom">
						<?php
						if ($tournament['Tournament']['team_size'] > 1) {
							echo $tournament['Tournament']['team_size'] . 'v' . $tournament['Tournament']['team_size'] . ' - ';
						}
						?>
						<?php echo $tournament['Tournament']['time_start']; ?>
					</strong>
				</a>
			<?php endforeach; ?>

		<?php endif; ?>
	</div>
</div>

<?php
// pr($tournaments);
?>