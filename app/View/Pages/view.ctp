<div class="view">
	<?php if ($logged_in && $is_admin): ?>
		<div style="float:right;">
			<?php echo $this->Html->link('Edit page', array('action' => 'edit', $page['Page']['id'])); ?>
		</div>
	<?php endif; ?>
	<h1><?php echo h($page['Page']['title']); ?></h1>

	<p>
		<small>
			<em>Updated: <?php echo $this->Time->nice($page['Page']['time_latest_update']); ?> by
				<?php echo $this->Html->link($page['LatestUpdateBy']['name'], array('controller' => 'users', 'action' => 'profile', $page['LatestUpdateBy']['id'])); ?>
			</em>
		</small>
	</p>

	<div>
		<?php echo $page['Page']['text']; ?>
	</div>
</div>