<div>
	<div style="float:right;">
		<?php echo $this->Html->link('Add images', array('action' => 'add')); ?>
	</div>
	<h1>Images database</h1>
	<div id="image-list">
		<?php foreach ($images as $image) : ?>
			<div>
				<?php
				echo $this->Html->link(
						  $this->Html->image('uploads/thumb_60h_' . $image['Image']['fileName']), '..' . DS . IMAGES_URL . 'uploads' . DS . $image['Image']['fileName'], array(
					 'escape' => false,
					 'class' => 'fancybox',
					 'rel' => 'images',
					 'title' => $image['Image']['title']
						  )
				);
				?>
				<strong><?php echo $image['Image']['title']; ?></strong>
				<span><?php echo $image['Image']['fileSize']; ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>