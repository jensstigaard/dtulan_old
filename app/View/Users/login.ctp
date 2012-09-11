<div class="users form">
<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo __('Login'); ?></legend>
    <?php
        echo $this->Form->input('email');
        echo $this->Form->input('password');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Log in')); ?>
</div>
