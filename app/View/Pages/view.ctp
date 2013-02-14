<div>
	<?php if ($is_admin): ?>
		<div style="float:right;">
			<?php
			echo $this->Html->link(
					  '<i class="icon-large icon-pencil"></i> Edit page', array(
				 'action' => 'edit',
				 $page['Page']['id']
					  ), array(
				 'escape' => false,
				 'class' => 'btn btn-inverse btn small'
			));
			?>
		</div>
	<?php endif; ?>
	<h1><?php echo $page['Page']['title']; ?></h1>

	<p>
		<small>
			<em>
				Updated: <?php echo $page['Page']['time_latest_update_nice']; ?> by
				<?php echo $this->Html->link($page['LatestUpdateBy']['name'], array('controller' => 'users', 'action' => 'view', $page['LatestUpdateBy']['id'])); ?>
			</em>
		</small>
	</p>

	<div>
		<?php echo $page['Page']['text']; ?>
	</div>
</div>