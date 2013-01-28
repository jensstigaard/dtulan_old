<div>
	<div style="float:right;">
		<?php echo $this->Html->link('Add images', array('action' => 'add')); ?>
	</div>
	<h1>Images database</h1>
	<div class="floated-list" id="list-images">
		<?php foreach ($images as $image) : ?>
			<div class="item" style="padding:5px 1px">
				<?php
				echo $this->Html->link(
						  $this->Html->image('uploads/thumb_210w_' . $image['Image']['fileName']), '..' . DS . IMAGES_URL . 'uploads' . DS . $image['Image']['fileName'], array(
					 'escape' => false,
					 'class' => 'fancybox',
					 'rel' => 'images',
					 'title' => $image['Image']['title']
						  )
				);
				?>
				<p style="margin:0;"><strong><?php echo $image['Image']['title']; ?></strong></p>
				<p style="margin:0;"><?php echo $image['Image']['fileSize']; ?></p>
				<?php
				echo $this->Html->link('<i class="icon-remove"></i> Delete', array(
					 'controller' => 'images',
					 'action' => 'delete', $image['Image']['id']
						  ), array(
					 'escape' => false,
					 'confirm' => 'Are you sure?',
					 'class' => 'btn btn-small btn-danger'
						  )
				);
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>