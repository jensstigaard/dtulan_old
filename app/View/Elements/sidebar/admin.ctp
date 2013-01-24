<?php if ($is_admin): ?>
	<div>
		<h1>User lookup</h1>
		<div id="user_lookup">
			<input type="text" name="string" id="user_lookup_input" />
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
						  '<i class="icon-coffee"></i> Candy/soda menus', array(
					 'controller' => 'food_menus',
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
						  '<i class="icon-coffee"></i> Candy/soda orders', array(
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
						  '<i class="icon-picture"></i> Image Database', array(
					 'controller' => 'images',
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
						  '<i class="icon-cloud"></i> LANS', array(
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
						  '<i class="icon-user"></i> New admin', array(
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
						  '<i class="icon-file-alt"></i> Pages', array(
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
						  '<i class="icon-money"></i> Payments', array(
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
						  '<i class="icon-food"></i> Pizza menus', array(
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
						  '<i class="icon-food"></i> Pizza types', array(
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
						  '<i class="icon-group"></i> Users', array(
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