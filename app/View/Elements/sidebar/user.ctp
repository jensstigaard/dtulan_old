<div>
	<h1 style="text-align:right;">
		<?php
		if (!empty($current_user['email_gravatar'])) {
		echo $this->Html->image(
				'http://www.gravatar.com/avatar/' . md5(strtolower($current_user['email_gravatar'])) . '?s=56', array(
			'style' => 'float:right;margin-left: 10px;margin-right:-5px;'
				)
		);
		}
		?>
<?php echo $current_user['name']; ?>
	</h1>
	<div style="clear:both;">
		<ul>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/user.gif', array('alt' => 'Profile')) . ' Profile', array('controller' => 'users', 'action' => 'profile'), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/reply.gif', array('alt' => 'Edit info')) . ' Edit personal data', array('controller' => 'users', 'action' => 'edit'), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/login.gif', array('alt' => 'Log out')) . ' Logout', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?></li>
		</ul>
	</div>
</div>