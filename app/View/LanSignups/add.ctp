<div class="form">
	<?php echo $this->Form->create('LanSignup', array('url' => array('controller' => 'lansignups', 'action' => 'add', $lan['Lan']['id']))); ?>
    <fieldset>
        <legend><?php echo __('Signup for ' . $lan['Lan']['title']); ?></legend>
		<ul>
			<li>Start time: <?php echo $this->Time->format('l F jS Y H:i', $lan['Lan']['time_start']); ?></li>
			<li>End time: <?php echo $this->Time->format('l F jS Y H:i', $lan['Lan']['time_end']); ?></li>
		</ul>

		<div class="lan_days">
			<?php
			echo $this->Form->input('LanDayUser.lan_day', array(
				'label' => 'Select days attending',
				'type' => 'select',
				'multiple' => 'checkbox',
				'options' => $lan_days
					)
			);

//			echo $this->Form->hidden('lan_id', array('value' => $lan['Lan']['id']));
			?>
		</div>
    </fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>

<?php pr($user); ?>
</div>