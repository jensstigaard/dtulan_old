<ul>
	<?php foreach ($menu_items as $menu_item): ?>
		<?php echo $this->element('menu_item', array('item' => $menu_item)); ?>
	<?php endforeach; ?>
</ul>