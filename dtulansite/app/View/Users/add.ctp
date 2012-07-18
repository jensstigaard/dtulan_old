<!-- app/View/Users/add.ctp -->
<div class="users form">
	<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Register user'); ?></legend>
		<?php
		echo $this->Form->input('name');
		echo $this->Form->input('email');
		echo $this->Form->input('gamertag');
		echo $this->Form->input('type', array('options' => array('guest' => 'Guest', 'student' => 'Student')));
		echo $this->Form->input('id_number', array('label' => 'Study Number', 'type' => 'Text'))
		?>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>