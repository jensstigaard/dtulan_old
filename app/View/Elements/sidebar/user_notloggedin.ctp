<div>
	<h1>Welcome</h1>
	<ul>
		<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/login.gif', array('alt' => 'Log in')) . ' Login', array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/user.gif', array('alt' => 'Forgot password')) . ' Forgot password?', array('controller' => 'users', 'action' => 'forgot_password'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/action_add.gif', array('alt' => 'Register user')) . ' Register user', array('controller' => 'users', 'action' => 'add'), array('escape' => false)); ?></li>

	</ul>
</div>