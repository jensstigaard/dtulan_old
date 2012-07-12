
<?php echo $this->Form->create('Registration'); ?>
    <fieldset>
        <legend><?php echo __('Edit User'); ?></legend>
    <?php
        echo $this->Form->input('first_name');
        echo $this->Form->input('last_name');
        echo $this->Form->input('email');
        echo $this->Form->input('gamertag');
        echo $this->Form->input('type', array( 
            'options' => array('g' => 'Guest', 's' => 'Student'),
            'default' => $registration['Registration']['type']));
        echo $this->Form->input('id_number', array('label' => 'Study Number', 'type' => 'text'))
        
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>

