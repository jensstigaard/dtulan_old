<?php
echo $this->Html->script(array('general'));
?>

<div>
	<?php if ($is_admin): ?>
		<div>
			<?php echo $this->Html->link('New tournament', array('controller' => 'tournaments', 'action' => 'add', $lan_id)); ?>
		</div>
	<?php endif; ?>

	<?php if (!count($tournaments)): ?>
		<p><i class="icon-exclamation-sign"></i> No tournaments published yet</p>
	<?php else: ?>

		<div class="floated-list" id="list-tournaments">
			<?php foreach ($tournaments as $tournament): ?>
				<a href="<?php echo $this->Html->url(array('controller' => 'tournaments', 'action' => 'view', $tournament['Tournament']['id'])); ?>" class="item">
					<span style="background-image:url('../../<?php echo $tournament['Game']['Image']['filePath']; ?>');">
						<strong><?php echo $tournament['Tournament']['time_start']; ?></strong>
					</span>
				</a>
			<?php endforeach; ?>

		<?php endif; ?>
	</div>
</div>

<?php
// pr($tournaments); ?>