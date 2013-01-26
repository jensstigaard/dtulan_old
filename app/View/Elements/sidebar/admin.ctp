<?php if ($is_admin): ?>
	<div>
		<h1>User lookup</h1>
		<div id="user_lookup">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-user"></i></span>
				<input type="text" class="span2" name="string" id="user_lookup_input" placeholder="Name / id-number / email" />
			</div>
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
						  '<i class="icon-coffee icon-large"></i> Candy/soda menus', array(
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
						  '<i class="icon-coffee icon-large"></i> Candy/soda orders', array(
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
						  '<i class="icon-screenshot icon-large"></i> Games', array(
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
						  '<i class="icon-picture icon-large"></i> Image Database', array(
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
						  '<i class="icon-sitemap icon-large"></i> LANS', array(
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
						  '<i class="icon-user icon-large"></i> New admin', array(
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
						  '<i class="icon-file-alt icon-large"></i> Pages', array(
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
						  '<i class="icon-money icon-large"></i> Payments', array(
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
						  '<i class="icon-food icon-large"></i> Pizza menus', array(
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
						  '<i class="icon-food icon-large"></i> Pizza types', array(
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
						  '<i class="icon-group icon-large"></i> Users', array(
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
						  '<i class="icon-qrcode icon-large"></i> QR-codes', array(
					 'controller' => 'qr_codes',
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