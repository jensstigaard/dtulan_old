<div class="form">
	<div style="float:right;">
		<?php if($logged_in && $is_admin): ?>
		<?php echo $this->Html->link('New pizza', array('action' => 'add')); ?>
		<?php endif; ?>
	</div>

	<h2>Pizzas</h2>
	<p>You'll see the list of available pizzas below</p>
	<table>
		<?php foreach ($pizza_categories as $pizza_category): ?>
			<tr>
				<th colspan="3"><?php echo $pizza_category['PizzaCategory']['title']; ?><br />
					<small><?php echo $pizza_category['PizzaCategory']['description'] ?></small>
				</th>
				<?php foreach ($pizza_category['PizzaCategoryType'] as $type): ?>
					<th><?php echo $type['PizzaType']['title_short']; ?></th>
				<?php endforeach; ?>
			</tr>
			<?php foreach ($pizza_category['Pizza'] as $pizza): ?>
				<tr>
					<td><?php echo $pizza['number']; ?></td>
					<td><?php echo $pizza['title']; ?></td>
					<td><?php echo $pizza['description']; ?></td>
					<?php
					foreach ($pizza['Prices'] as $price):
						?>
						<td><?php
			if ($price != 0) {
				echo'<span>'.$price.'</span>,-';
			}
						?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>

		<?php endforeach; ?>

	</table>

<!--<pre>-->
	<?php
//print_r($pizza_categories);
	?>
	<!--</pre>-->
</div>