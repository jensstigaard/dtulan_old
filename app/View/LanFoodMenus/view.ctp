<?php
echo $this->Html->css('pizzas', null, array('inline' => false));

if ($is_orderable_food) {
	echo $this->Html->script(array('order/food'), FALSE);
}
?>
<div class="box">

	<h1><?php echo $food_menu['FoodMenu']['title']; ?></h1>
	<div>
		<?php echo $food_menu['FoodMenu']['description']; ?>
	</div>
</div>

<div class="box">
	<h2>Products</h2>
	<?php if (!count($categories)): ?>
		<p>No items at this menu yet</p>
	<?php else: ?>
		<table class="food_list">
			<?php foreach ($categories as $category): ?>
				<thead>
					<tr>
						<th><?php echo $category['FoodCategory']['title']; ?></th>
						<th>Price</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($category['Food'] as $food): ?>
						<?php
						$if_admin_and_unavailable = '';
						if (!$food['available']) {
							$if_admin_and_unavailable = ' gray';
						}
						?>
						<tr class="<?php echo $if_admin_and_unavailable; ?>">
							<td>
								<span><?php echo $food['title']; ?></span>
								<small class="description"><?php echo $food['description']; ?></small>
							</td>
							<td class="price">
								<span><?php echo $food['price']; ?></span> DKK
								<span class="hidden item_id"><?php echo $food['id']; ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>

<div class="hidden_images">
	<?php
	echo $this->Html->image('16x16_PNG/add.png', array('class' => 'image_add'));
	echo $this->Html->image('16x16_PNG/cancel.png', array('class' => 'image_remove'));
	?>
</div>