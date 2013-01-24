<div>
	<div style="float:right;">
		<?php echo $this->Html->link('<i class="icon-plus-sign"></i> New game', array('controller' => 'games', 'action' => 'add'),array('escape'=>false)); ?>
	</div>
	
	<h1>Games</h1>
	<div class="list-floated" id="list-games">
		<?php foreach ($games as $game) : ?>
			<div>
				<?php echo $this->Html->image('uploads/thumb_60h_' . $game['Image']['id'] . '.' . $game['Image']['ext']); ?>
				<strong><?php echo $game['Game']['title']; ?></strong><br />
				<?php
				echo $this->Html->link('<i class="icon-edit"></i>', array(
					 'controller' => 'games',
					 'action' => 'edit',
					 $game['Game']['id']
						  ), array(
					 'escape' => false
				));
				?>
				<?php
				echo $this->Form->postLink('<i class="icon-remove"></i>', array(
					 'controller' => 'games',
					 'action' => 'delete',
					 $game['Game']['id']
						  ), array(
					 'escape' => false,
					 'confirm' => 'Are you sure?'
				));
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>