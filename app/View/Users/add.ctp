<?php echo $this->Html->script(array('user_register'), FALSE); ?>
<div>
	<?php echo $this->Form->create(); ?>
	<fieldset>
		<legend><?php echo __('Register user'); ?></legend>
		<?php
		echo $this->Form->input('name', array('label' => __('Full name')));
		echo $this->Form->input('email');
		echo $this->Form->input('phonenumber');
		?>
		<div>
			<p>Your phone number is not visible at the website. We're giving the fire department information about all our participants during events.</p>
		</div>
		<?php
		echo $this->Form->input('gamertag', array('required' => false));
		echo $this->Form->input('type', array(
			 'options' => array(
				  'guest' => 'Guest',
				  'student' => 'Student'
			 ),
			 'id' => 'typeSelect'
		));
		?>
		<div id="id_number">
			<?php
			echo $this->Form->input('id_number', array('label' => 'Study Number', 'type' => 'hidden', 'maxlength' => 7))
			?>
		</div>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>
