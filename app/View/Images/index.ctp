<div>
	<div style="float:right;">
		<?php echo $this->Html->link('Add images', array('action' => 'add')); ?>
	</div>
	<h1>Images database</h1>
	<div class="floated-list" id="list-images">
		<?php foreach ($images as $image) : ?>
			<div class="item">
				<?php
				echo $this->Html->link(
						  $this->Html->image('uploads/thumb_60h_' . $image['Image']['fileName']), '..' . DS . IMAGES_URL . 'uploads' . DS . $image['Image']['fileName'], array(
					 'escape' => false,
					 'class' => 'fancybox',
					 'rel' => 'images',
					 'title' => $image['Image']['title']
						  )
				);
				?><br />
				<strong><?php echo $image['Image']['title']; ?></strong><br />
				<span><?php echo $image['Image']['fileSize']; ?></span><br />
				<?php
				echo $this->Form->postLink('<i class="icon-remove"></i>', array(
					 'controller' => 'images',
					 'action' => 'delete', $image['Image']['id']
						  ), array(
					 'escape' => false,
					 'confirm' => 'Are you sure?'
						  )
				);
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>