<div>
	<h1>Edit personal data</h1>
	<p>You are able to change some of your personal data.</p>

	<?php echo $this->Form->create(); ?>
	<div>
		<?php echo $this->Form->input('name'); ?>
	</div>
	<div>
		<?php echo $this->Form->input('phonenumber'); ?>
		<p style="margin:0 7px;">Your phone number is not visible at the website. We're giving the fire department information about all our participants during events.</p>
	</div>
	<div>
		<?php echo $this->Form->input('gamertag'); ?>
	</div>
	<div>
		<strong>Email subscription</strong>
		<?php echo $this->Form->input('email_subscription', array('label' => 'Get emails when	new events are announced')); ?>
	</div>
	<div>
		<div style="margin: 0 3px;">
			<strong>Avatar</strong>
			<?php
			echo $this->Html->image(
					  'http://www.gravatar.com/avatar/' . md5(strtolower($email_gravatar)), array(
				 'style' => 'float:right;margin-left:10px;margin-right:5px;'
					  )
			);
			?>
			<p>Customize your avatar at <?php echo $this->Html->link('gravatar.com', 'http://gravatar.com', array('target' => '_blank')); ?></p>
			<p>It's free to use and is used at many other websites. Make sure that you're using the correct email.</p>
		</div>
		<?php echo $this->Form->input('email_gravatar', array('label' => 'Gravatar email')); ?>
	</div>
	<?php echo $this->Form->end(__('Save')); ?>
</div>