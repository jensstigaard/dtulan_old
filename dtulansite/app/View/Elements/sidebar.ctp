<div style="margin-bottom:5px;">
	<?php
	if ($logged_in):
		?>
		<p>Welcome <?php echo $current_user['name']; ?>.</p>
		<ul style="margin-bottom: 30px;">


			<li><?php echo $this->Html->link('Profile', array('controller' => 'users', 'action' => 'profile')); ?></li>
			<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
			<?php if ($is_admin): ?>
			</ul>
			<ul>
				<li><?php echo $this->Html->link('Admin panel', array('controller' => 'admin', 'action' => 'panel')); ?></li>
				<li><?php echo $this->Html->link('Lans', array('controller' => 'lans', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('LAN signups', array('controller' => 'lansignups', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Pages', array('controller' => 'pages', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link('Pizzas', array('controller' => 'pizzas', 'action' => 'index')); ?></li>
			<?php endif; ?>
		</ul>

	<?php else: ?>
		<ul><li><?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?></li></ul>
	<?php endif; ?>
</div>