<?php if ($is_admin): ?>
	<div>
		<h1>User lookup</h1>
		<div id="user_lookup">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-user"></i></span>
				<input type="text" name="string" id="user_lookup_input" placeholder="Name / id-number / email" data-link="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lookup')); ?>" data-redirect="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'view')); ?>" />
			</div>
		</div>
	</div>
	<div>
		<h1>Admin menu</h1>
		<ul>


			<li>
				<?php
				echo $this->Html->link(
						  '<i class="icon-large icon-user-md"></i> Admins', array(
					 'controller' => 'admins',
					 'action' => 'index',
					 'admin' => true,
						  ), array(
					 'escape' => false
						  )
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						  '<i class="icon-large icon-user-md"></i> Admin new', array(
					 'controller' => 'admins',
					 'action' => 'add',
					 'admin' => true,
						  ), array(
					 'escape' => false
						  )
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						  '<i class="icon-large icon-coffee"></i> Candy/soda menus', array(
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
						  '<i class="icon-large icon-coffee"></i> Candy/soda orders', array(
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
						  '<i class="icon-large icon-screenshot"></i> Games', array(
					 'controller' => 'games',
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
						  '<i class="icon-large icon-picture"></i> Image Database', array(
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
						  '<i class="icon-large icon-sitemap"></i> LANS', array(
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
						  '<i class="icon-large icon-quote-right"></i> News database', array(
					 'controller' => 'news_items',
					 'action' => 'index',
					 'crew' => true,
						  ), array(
					 'escape' => false
						  )
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						  '<i class="icon-large icon-quote-right"></i> News add', array(
					 'controller' => 'news_items',
					 'action' => 'add',
					 'crew' => true,
						  ), array(
					 'escape' => false
						  )
				);
				?>
			</li>

			<li>
				<?php
				echo $this->Html->link(
						  '<i class="icon-large icon-file-alt"></i> Pages', array(
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
						  '<i class="icon-large icon-money"></i> Payments', array(
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
						  '<i class="icon-large icon-food"></i> Pizza menus', array(
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
						  '<i class="icon-large icon-food"></i> Pizza types', array(
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
						  '<i class="icon-large icon-group"></i> Users', array(
					 'controller' => 'users',
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
						  '<i class="icon-large icon-qrcode"></i> QR-codes', array(
					 'controller' => 'qr_codes',
					 'action' => 'index',
					 'admin' => true
						  ), array(
					 'escape' => false
						  )
				);
				?>
			</li>
		</ul>
	</div>
<?php endif; ?>