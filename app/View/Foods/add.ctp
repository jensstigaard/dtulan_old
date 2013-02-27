<div class="box">
	<div style="float:right">
		<?php echo $this->Html->link('Back to food', array('action' => 'index')); ?>
	</div>
	<?php echo $this->Form->create(); ?>
	<div>
		<?php
		echo $this->Form->inputs(array(
			 'legend' => 'New Food in ' . $category['FoodCategory']['title'],
			 'title',
			 'description' => array('rows' => 2),
			 'price',
			 'available'
		));
		?>
	</div>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>