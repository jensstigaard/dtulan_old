<?php
echo $this->Html->css('pizzas', null, array('inline' => false));
echo $this->Html->script(array('pizzas'), FALSE);

if ($is_orderable) {
	echo $this->Html->script(array('order/pizza'), FALSE);
}
?>

<div>
	<h1><?php echo $pizza_menu['PizzaMenu']['title']; ?></h1>
	<p><?php echo $pizza_menu['PizzaMenu']['description']; ?></p>
	<div id="pizza_list" class="accordion">
		<?php foreach ($pizza_categories as $pizza_category): ?>
			<?php if (count($pizza_category['Pizza']) || $is_admin): ?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a style="font-size: 13pt;" class="accordion-toggle" data-toggle="collapse" data-parent="#pizza_list" href="#category<?php echo $pizza_category['PizzaCategory']['id']; ?>">
							<?php echo $pizza_category['PizzaCategory']['title']; ?>
						</a>
					</div>
					<div id="category<?php echo $pizza_category['PizzaCategory']['id']; ?>" class="accordion-body collapse">
						<div class="accordion-inner">
							<table class="pizza_list">
								<thead>
									<tr class="pizza_category">
										<th colspan="2" style="font-weight:normal;">
											<?php echo nl2br($pizza_category['PizzaCategory']['description']); ?>
										</th>
										<?php foreach ($pizza_category['PizzaType'] as $type): ?>
											<th style="vertical-align: bottom; text-align: center;">
												<span title="<?php echo $type['title']; ?>"><?php echo $type['title_short']; ?></span>
											</th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<?php if (!count($pizza_category['Pizza'])): ?>
									<tr>
										<td colspan="7">
											<?php echo $this->Html->image('24x24_PNG/001_11.png', array('style' => 'vertical-align:middle')); ?>
											<strong>
												No pizzas in this category.
											</strong>
											<small>(Not visible to guests)</small>

										</td>
									</tr>
								<?php else: ?>
									<tbody>
										<?php
										foreach ($pizza_category['Pizza'] as $pizza):
											$if_admin_and_unavailable = '';

											if ($pizza['available'] || $is_admin):
												if (!$pizza['available']) {
													$if_admin_and_unavailable = ' gray';
												}
												?>
												<tr class="pizza_item<?php echo $if_admin_and_unavailable; ?>">
													<td class="number"><?php echo $pizza['number']; ?></td>
													<td>
														<span class="title"><?php echo $pizza['title']; ?></span><br />
														<small class="desc"><?php echo $pizza['description']; ?></small>
													</td>
													<?php
													if (isset($pizza['Prices'])) {
														foreach ($pizza['Prices'] as $price_type_id => $price_info):
															?>
															<td class="price">
																<?php if ($price_info['price'] != 0): ?>
																	<span<?php echo $pizza['available'] ? ' class="available"' : '' ?>><?php echo $price_info['price']; ?></span>,-
																	<span class="hidden price_id"><?php echo $price_info['id']; ?></span>
																<?php endif; ?>
															</td>
															<?php
														endforeach;
													}
													?>
												</tr>
											<?php endif; ?>
										<?php endforeach; ?>
									</tbody>
								<?php endif; ?>
							</table>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>

	</div>
	<div style="clear:both;"></div>
	<div class="hidden_images">
		<?php
		echo $this->Html->image('16x16_PNG/add.png', array('class' => 'image_add'));
		echo $this->Html->image('16x16_PNG/cancel.png', array('class' => 'image_remove'));
		?>
	</div>
</div>