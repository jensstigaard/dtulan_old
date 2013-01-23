<div>
	<h1>Welcome</h1>
	<ul>
		<li><?php echo $this->Html->link('<i class="icon-unlock"></i> Login', array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('<i class="icon-key"></i> Forgot your password?', array('controller' => 'users', 'action' => 'forgot_password'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('<i class="icon-plus"></i> Register user', array('controller' => 'users', 'action' => 'add'), array('escape' => false)); ?></li>
	</ul>
</div>