<div>
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Add  Food Menu in ' . $lan_title); ?></legend>

		<?php if (!count($foodMenus)): ?>
			<p>No menus can be added</p>
		<?php else: ?>
			<?php
			echo $this->Chosen->select(
					'food_menu_id', $foodMenus, array(
				'data-placeholder' => 'Pick Food Menu...',
					)
			);
			?>
		<?php endif; ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>