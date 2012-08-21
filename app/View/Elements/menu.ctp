<?php
$menu_items = $this->requestAction(array('controller' => 'pages', 'action' => 'menu'));
?>
<ul>
	<?php foreach ($menu_items as $menu_item): ?>
		<?php if ($menu_item['Page']['public']): ?>
			<li class="menuItemTop"><?php echo $this->requestAction(array('controller' => 'pages', 'action' => 'menuItem', $menu_item['Page']['id']), array('return')); ?>
				<?php if (count($menu_item['Underpage'])): ?>
					<ul class="dropdown">
						<?php foreach ($menu_item['Underpage'] as $item): ?>
						<?php if ($item['public']): ?>
							<li class="menuItem"><?php echo $this->requestAction(array('controller' => 'pages', 'action' => 'menuItem', $item['id']), array('return')); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php
//pr($menu_items);