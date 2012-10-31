<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Add pizza menu in ' . $lan_title); ?></legend>

		<?php if (!count($pizzaMenus)): ?>
			<p>No pizza menus can be added</p>
		<?php else: ?>
			<?php echo $this->Form->input('LanPizzaMenu.pizza_menu_id'); ?>
		<?php endif; ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>