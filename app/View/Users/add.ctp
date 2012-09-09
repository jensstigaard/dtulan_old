<?php echo $this->Html->script(array('jquery','userRegister'), FALSE); ?>
<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Register user'); ?></legend>
		<?php
		echo $this->Form->input('name');
		echo $this->Form->input('email');
		echo $this->Form->input('gamertag');
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
			echo $this->Form->input('id_number', array('label' => 'Study Number', 'type' => 'Text', 'maxlength' => 7))
			?>
		</div>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>