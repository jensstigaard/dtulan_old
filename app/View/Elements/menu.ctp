<?php
$page_model = ClassRegistry::init('Page');
?>
<ul>
	<?php foreach ($page_model->getMenuItems() as $menu_item): ?>
		<?php echo $this->element('menu_item', array('item' => $menu_item)); ?>
	<?php endforeach; ?>
</ul>