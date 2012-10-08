<?php
echo $this->Html->css('pizzas', null, array('inline' => false));

if (isset($sweets_current_lan_id)) {
	echo $this->Html->script(array('jquery', 'sweets_orderable'), FALSE);
}
?><div>
	<?php if ($is_admin): ?>
		<div style="float:right;">
			<?php echo $this->Html->link('New sweet', array('action' => 'add')); ?>
		</div>
	<?php endif; ?>


	<h1>Sweets and soda</h1>
	<p>A list of available sweets and soda will appear below.</p>

	<?php if (isset($sweets)): ?>
		<table class="sweets_list">
			<thead>
				<tr>
					<th>Item</th>
					<th>Price</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($sweets as $sweet): ?>
					<?php
					$if_admin_and_unavailable = '';
					if (!$sweet['Sweet']['available']) {
						$if_admin_and_unavailable = ' gray';
					}
					?>
					<tr class="<?php echo $if_admin_and_unavailable; ?>">
						<td>
							<span><?php echo $sweet['Sweet']['title']; ?></span>
							<small class="description"><?php echo $sweet['Sweet']['description']; ?></small>
						</td>
						<td class="price">
							<span><?php echo $sweet['Sweet']['price']; ?></span> DKK
							<span class="hidden item_id"><?php echo $sweet['Sweet']['id']; ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

<div class="hidden_images">
	<?php
	echo $this->Html->image('16x16_PNG/add.png', array('class' => 'image_add'));
	echo $this->Html->image('16x16_PNG/cancel.png', array('class' => 'image_remove'));
	?>
</div>