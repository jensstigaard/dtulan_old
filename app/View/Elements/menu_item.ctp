<?php
if (isset($item['Page'])) {
	$page = $item['Page'];
} else {
	$page = $item;
}

if ($page['public'] && $page['in_menu']) {

	if ($page['parent_id'] == 0) {
		$class = 'menuItemTop';
	} else {
		$class = 'menuItem';
	}

	if (isset($item['Underpage']) && count($item['Underpage'])) {
		$children = $item['Underpage'];

		if (count($children)) {
			$has_children = true;
			$class.=' parent';
		}
	}

	$page_model = ClassRegistry::init('Page');
	$page_url = $page_model->getUrl($page);
	?>
	<li class="<?php echo $class; ?>">
		<?php echo $this->Html->link($page['title'], $page_url); ?>
		<?php if (isset($has_children)): ?>
			<ul class="dropdown">
				<?php foreach ($children as $underpage_item): ?>
					<?php echo $this->element('menu_item', array('item' => $underpage_item)); ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</li>
<?php } ?>