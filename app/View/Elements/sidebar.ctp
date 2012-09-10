<?php if ($logged_in): ?>
	<?php if (isset($waves) && count($waves)): ?>
		<div id="pizza_waves">
			<h1>Pizza waves available</h1>
			<?php if ($current_wave == ''): ?>
				<div class="notice" style="font-size:11pt; padding:5px;">
					Choose a wave below to order pizzas.
				</div>
			<?php endif; ?>
			<?php
			$last_date = '';
			foreach ($waves as $wave):
				$this_date = $this->Time->format('Y-m-d', $wave['PizzaWave']['time_start']);
				?>
				<?php if ($last_date != $this_date): ?>
					<?php if ($last_date != '') : ?>
						<br />
						<hr />
					<?php endif; ?>
					<h3><?php
				if ($this->Time->isToday($wave['PizzaWave']['time_start'])) {
					echo'Today';
				} elseif ($this->Time->isToday($wave['PizzaWave']['time_start'])) {
					echo'Tomorrow';
				} else {
					echo $this->Time->format('D, M jS', $wave['PizzaWave']['time_start']);
				}
					?></h3>
					<?php $last_date = $this_date; ?>
				<?php endif; ?>
				<div>
					<?php
					$content = $this->Time->format('H:i', $wave['PizzaWave']['time_start']) . ' - ' . $this->Time->format('H:i', $wave['PizzaWave']['time_end']);
					if ($wave['PizzaWave']['id'] == $current_wave['PizzaWave']['id']) :
						?>
						<strong title="Currently selected wave"><?php echo $content; ?>
							<?php if ($is_admin): ?>
								<?php echo $this->Html->image('16x16_GIF/reply.gif', array('url' => array('controller' => 'pizza_waves', 'action' => 'edit', $wave['PizzaWave']['id']))); ?>
							<?php endif; ?>
						</strong>
					<?php else: ?>
						<?php echo $this->Html->link($content, array('action' => 'index', 'wave_id' => $wave['PizzaWave']['id'])); ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if (isset($is_orderable) && $is_orderable): ?>
		<div id="pizza_order">
			<h1>Your order</h1>
			<table>
				<thead>
					<tr>
						<th colspan="3">Pizza</th>
						<th colspan="3">Price</th>
					</tr>

				</thead>
				<tbody>
					<tr>
						<td style="text-align:right;" colspan="3">Total:</td>
						<td style="width:50px;" class="pizza_order_total">0</td>
						<td>DKK</td>
						<td></td>
					</tr>
				</tbody>

			</table>
			<div class="pizza_order_buttons hidden">
				<small><?php echo $this->Js->link('Clear order ' . $this->Html->image('16x16_GIF/action_delete.gif'), '#', array('class' => 'pizza_order_clear', 'escape' => false)); ?></small>
				<?php echo $this->Js->link('Submit order', array('controller' => 'pizza_orders', 'action' => 'add'), array('class' => 'pizza_order_submit')); ?>
				<div class="hidden"><?php echo $current_wave['PizzaWave']['id']; ?></div>
				<div style="clear:both;"></div>
			</div>
			<?php echo $this->Form->end(); ?>
			<div class="pizza_order_sending hidden"></div>
			<div class="pizza_order_success hidden">Pizza order submitted</div>
			<div class="pizza_order_errors hidden"></div>
		</div>
	<?php endif;
	?>
	<div>
		<h1 style="text-align:right;"><?php echo $this->Html->image('http://www.gravatar.com/avatar/'.md5(strtolower($current_user['email'])).'?s=56', array('style' => 'float:right;margin-left: 10px;margin-right:-5px;')); ?><?php echo $current_user['name']; ?></h1>
		<ul>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/user.gif', array('alt' => 'Profile')) . ' Profile', array('controller' => 'users', 'action' => 'profile'), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/reply.gif', array('alt' => 'Edit info')) . ' Edit personal data', array('controller' => 'users', 'action' => 'edit'), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/login.gif', array('alt' => 'Log out')) . ' Logout', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?></li>
			<?php if ($is_admin): ?>
			</ul>
		</div>
		<div>
			<h1>User lookup</h1>
			<div id="user_lookup">
				<?php echo $this->Form->input('string', array('label' => false)); ?>
				<div class="hidden" id="urlLookup"><?php echo $this->Html->link('#', array('controller' => 'users', 'action' => 'lookup')); ?></div>
				<div class="hidden" id="urlRedirect"><?php echo $this->Html->link('#', array('controller' => 'users', 'action' => 'profile')); ?></div>
			</div>
		</div>
		<div>
			<h1>Admin</h1>
			<ul>
				<li><?php echo $this->Html->link('LANS', array('controller' => 'lans', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Pages', array('controller' => 'pages', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Pizzas', array('controller' => 'pizza_categories', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Payments', array('controller' => 'payments', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Tournaments', array('controller' => 'tournaments', 'action' => 'index')); ?></li>
			<?php endif; ?>
		</ul>
	</div>

<?php else: ?>
	<div>
		<h1>Welcome</h1>
		<ul>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/login.gif', array('alt' => 'Log out')) . ' Login', array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/user.gif', array('alt' => 'Profile')) . ' Register user', array('controller' => 'users', 'action' => 'add'), array('escape' => false)); ?></li>
		</ul>
	</div>

<?php endif; ?>