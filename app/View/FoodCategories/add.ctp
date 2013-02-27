<?php echo $this->Html->script(array('ckeditor/ckeditor'), FALSE); ?>

<div class="box">
	<div style="float:right">
		<?php echo $this->Html->link('Back to ' . $food_menu['FoodMenu']['title'], array('controller' => 'food_menus', 'action' => 'view', $food_menu['FoodMenu']['id'])); ?>
	</div>
	<?php echo $this->Form->create(); ?>
	<div>
		<?php
		echo $this->Form->inputs(array(
			'legend' => 'Add Food Category to ' . $food_menu['FoodMenu']['title'],
			'title',
			'description' => array(
				'rows' => 2,
				'class' => 'ckeditor'),
		));
		?>
	</div>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>