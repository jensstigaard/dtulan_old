<div class="view">
	<?php if ($is_admin): ?>
		<div style="float:right;">
			<?php echo $this->Html->link($this->Html->image('16x16_GIF/reply.gif', array('alt' => 'Edit page')) . 'Edit page', array('action' => 'edit', $page['Page']['id']), array('escape' => false)); ?>
		</div>
	<?php endif; ?>
	<h1><?php echo h($page['Page']['title']); ?></h1>

	<p>
		<small>
			<em>
				Updated: <?php echo $page['Page']['time_latest_update_nice']; ?> by
				<?php echo $this->Html->link($page['LatestUpdateBy']['name'], array('controller' => 'users', 'action' => 'profile', $page['LatestUpdateBy']['id'])); ?>
			</em>
		</small>
	</p>

	<div>
		<?php echo $page['Page']['text']; ?>
	</div>
</div>