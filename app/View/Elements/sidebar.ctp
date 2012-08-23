<div style="margin-bottom:5px;">
	<?php
	if ($logged_in):
		?>
		<p>Welcome <?php echo $current_user['name']; ?>.</p>
		<ul style="margin-bottom: 30px;">


			<li><?php echo $this->Html->link('Profile', array('controller' => 'users', 'action' => 'profile')); ?></li>
			<li><?php echo $this->Html->link('Tournament', array('controller' => 'tournaments', 'action' => 'view')); ?></li>
			<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
			<?php if ($is_admin): ?>
			</ul>
			<h2>Admin</h2>
			<ul>
				<li><?php echo $this->Html->link('LANS', array('controller' => 'lans', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Pages', array('controller' => 'pages', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Pizzas', array('controller' => 'pizzacategories', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Payments', array('controller' => 'payments', 'action' => 'index')); ?></li>
			<?php endif; ?>
		</ul>

	<?php else: ?>
		<ul>
			<li><?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?></li>
			<li><?php echo $this->Html->link('Register user', array('controller' => 'users', 'action' => 'add')); ?></li>
		</ul>
	<?php endif; ?>
</div>