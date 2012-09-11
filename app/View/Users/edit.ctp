<div>
	<h1>Edit personal data</h1>
	<p>You are able to change some of your personal data.</p>
	<?php echo $this->Form->create(); ?>
	<?php echo $this->Form->input('User.gamertag', array('value' => $user['User']['gamertag'])); ?>
	<div>
		<strong>Avatar</strong>
		<?php
		echo $this->Html->image(
				'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '', array(
			'style' => 'float:right;margin-left:10px;margin-right:5px;'
				)
		);
		?>
		<p>Customize your avatar at <?php echo $this->Html->link('gravatar.com', 'http://gravatar.com', array('target' => '_blank')); ?>.</p>
		<p>It's free to use and is used at many other websites. Make sure that you're using the correct email.</p>
		<?php echo $this->Form->input('User.email_gravatar', array('value' => $user['User']['email_gravatar'])); ?>
	</div>
	<?php echo $this->Form->end(__('Save')); ?>
</div>