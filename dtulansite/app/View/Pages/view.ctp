<div class="view">
	<?php if($logged_in && $is_admin): ?>
	<div style="float:right;">
		<?php echo $this->Html->link('Edit page', array('action' => 'edit', $page['Page']['id'])); ?>
	</div>
	<?php endif; ?>
	<h2><?php echo h($page['Page']['title']); ?></h2>

	<p>
		<small><em>Updated: <?php echo $page['Page']['time_latest_update']; ?> by <?php echo $page['LatestUpdateBy']['name']; ?></em></small>
	</p>

	<p><?php echo $page['Page']['text']; ?></p>
</div>