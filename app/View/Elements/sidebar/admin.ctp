<?php if ($is_admin): ?>
	<div>
		<h1>User lookup</h1>
		<div id="user_lookup">
			<?php echo $this->Form->input('string', array('label' => false)); ?>
			<div class="hidden" id="urlLookup"><?php echo $this->Html->link('#', array('controller' => 'users', 'action' => 'lookup')); ?></div>
			<div class="hidden" id="urlRedirect"><?php echo $this->Html->link('#', array('controller' => 'users', 'action' => 'profile')); ?></div>
		</div>
	</div>
	<div>
		<h1>Admin menu</h1>
		<ul>
			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/candy.png') . ' Food orders', array(
					'controller' => 'food_orders',
					'action' => 'index'
						), array(
					'escape' => false
						)
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/games.png') . ' LANS', array(
					'controller' => 'lans',
					'action' => 'index'
						), array(
					'escape' => false
						)
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/001_55.png') . ' New admin', array(
					'controller' => 'admins',
					'action' => 'add'
						), array(
					'escape' => false
						)
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/001_43.png') . ' Pages', array(
					'controller' => 'pages',
					'action' => 'index'
						), array(
					'escape' => false
						)
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/payment_cash.png') . ' Payments', array(
					'controller' => 'payments',
					'action' => 'index'
						), array(
					'escape' => false
						)
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/pizza.png') . ' Pizza menus', array(
					'controller' => 'pizza_menus',
					'action' => 'index'
						), array(
					'escape' => false
						)
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/pizza.png') . ' Pizza types', array(
					'controller' => 'pizza_types',
					'action' => 'index'
						), array(
					'escape' => false
						)
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						$this->Html->image('24x24_PNG/001_57.png') . ' Users', array(
					'controller' => 'users',
					'action' => 'index'
						), array(
					'escape' => false
						)
				);
				?>
			</li>
		</ul>
	</div>
<?php endif; ?>