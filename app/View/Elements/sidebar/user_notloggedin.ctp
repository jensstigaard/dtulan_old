<div>
	<h1>Welcome</h1>
	<ul>
		<li><?php echo $this->Html->link('<i class="icon-unlock icon-large"></i> Login', array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('<i class="icon-key icon-large"></i> Forgot your password?', array('controller' => 'users', 'action' => 'forgot_password'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('<i class="icon-plus-sign icon-large"></i> Register user', array('controller' => 'users', 'action' => 'add'), array('escape' => false)); ?></li>
	</ul>
</div>