<!-- app/View/Users/add.ctp -->
<div class="users form">
<?php echo $this->Form->create('Registration'); ?>
    <fieldset>
        <legend><?php echo __('Register User'); ?></legend>
    <?php
        echo $this->Form->input('first_name');
        echo $this->Form->input('last_name');
        echo $this->Form->input('email');
        echo $this->Form->input('gamertag');
        echo $this->Form->input('type', array( 'options' => array('guest' => 'Guest', 'student' => 'Student')));
        echo $this->Form->input('study_number')
        
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>