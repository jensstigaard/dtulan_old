<!-- app/View/Users/activate.ctp -->
<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Enter password'); ?></legend>
    <?php
        echo $this->Form->input('password');
        echo $this->Form->input('password_confim');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
