<?php
echo $this->Html->css('layout.lan', null, array('inline' => false));
echo $this->Html->css('layout.general', null, array('inline' => false));
echo $this->Html->script(array('ajax/all_links'));
?>

<div class="ajax_area" id="tournaments">
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('New tournament', array('controller' => 'tournaments', 'action' => 'add', $lan_id)); ?>
		</div>
	<?php endif; ?>

	<?php if (!count($tournaments)): ?>
		<p><i class="icon-exclamation-sign"></i> No tournaments published yet</p>
	<?php else: ?>

		<div class="list-floated" id="list-tournaments">
			<?php foreach ($tournaments as $tournament): ?>
				<a href="<?php echo $this->Html->url(array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?>" class="item">
					<span style="background-image:url('../../<?php echo $tournament['Game']['Image']['filePath']; ?>');width:<?php echo $tournament['Game']['Image']['imageWidth']; ?>px;height:<?php echo $tournament['Game']['Image']['imageHeight']; ?>px">
						<strong><?php echo $tournament['Tournament']['time_start']; ?></strong>
					</span>
				</a>
			<?php endforeach; ?>

		<?php endif; ?>
	</div>
</div>

<?php pr($tournaments); ?>