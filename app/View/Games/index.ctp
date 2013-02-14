<div>
	<div style="float:right;">
		<?php echo $this->Html->link('<i class="icon-plus-sign"></i> New game', array('controller' => 'games', 'action' => 'add'), array('escape' => false, 'class' => 'btn btn-inverse')); ?>
	</div>

	<h1>Games</h1>
	<div class="floated-list" id="list-games">
		<?php foreach ($games as $game) : ?>
			<div class="item" style="padding: 5px 1px;">
				<div>
					<?php echo $this->Html->image('uploads/thumb_200x120_' . $game['Image']['id'] . '.' . $game['Image']['ext']); ?>
				</div>
				<div>
					<strong><?php echo $game['Game']['title']; ?></strong>
				</div>
				<div class="btn-group">
					<?php
					echo $this->Html->link('<i class="icon-edit"></i> Edit', array(
						 'controller' => 'games',
						 'action' => 'edit',
						 $game['Game']['id']
							  ), array(
						 'escape' => false,
						 'class' => 'btn btn-warning btn-small'
					));
					?>
					<?php
					echo $this->Html->link('<i class="icon-remove"></i> Delete', array(
						 'controller' => 'games',
						 'action' => 'delete',
						 $game['Game']['id']
							  ), array(
						 'escape' => false,
						 'confirm' => 'Are you sure?',
						 'class' => 'btn btn-danger btn-small'
					));
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>