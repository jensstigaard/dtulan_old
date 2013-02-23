<?php echo $this->Html->script(array('user_register'), FALSE); ?>
<div>
	<?php echo $this->Form->create(); ?>
	<fieldset>
		<legend><?php echo __('Register user'); ?></legend>
		<table>
			<tbody>
				<tr style="background: none;">
					<td style="border:none;"><?php echo $this->Form->input('name', array('label' => __('Full name'))); ?></td>
					<td style="border:none;vertical-align: middle;"></td>
				</tr>
				<tr style="background: none;">
					<td style="border:none;"><?php echo $this->Form->input('email'); ?></td>
					<td style="border:none;vertical-align: middle;">
						<a href="#" class="tooltips btn btn-inverse btn-mini" title="Please provide a valid email, we may send you an activation email to make sure it's you">
							<i class="icon-info-sign icon-large"></i>
						</a>
					</td>
				</tr>
				<tr style="background: none;">
					<td style="border:none;">
						<?php
						echo $this->Form->input('phonenumber', array(
							 'label' => 'Phone number'
						));
						?>
					</td>
					<td style="border:none;vertical-align: middle;">
						<a href="#" class="tooltips btn btn-inverse btn-mini" title="Please enter your phone number. No prefix, please. Your phone number is not visible at the website. We're giving the fire department information about all our participants during events.">
							<i class="icon-info-sign icon-large"></i>
						</a>
					</td>
				</tr>
				<tr style="background: none;">
					<td style="border:none;"><?php echo $this->Form->input('gamertag', array('required' => false)); ?></td>
					<td style="border:none;vertical-align: middle;">
						<a href="#" class="tooltips btn btn-inverse btn-mini" title="Optional field. Is showed at tournament-pages and such.">
							<i class="icon-info-sign icon-large"></i>
						</a>
					</td>
				</tr>
				<tr style="background: none;">
					<td style="border:none;">
						<?php
						echo $this->Form->input('type', array(
							 'options' => array(
								  'guest' => 'Guest',
								  'student' => 'Student'
							 ),
							 'id' => 'typeSelect'
						));
						?>
					</td>
					<td style="border:none;vertical-align: middle;">
						<a href="#" class="tooltips btn btn-inverse btn-mini" title="Choose Student if you can provide a studynumber from DTU. Otherwise choose Guest">
							<i class="icon-info-sign icon-large"></i>
						</a>
					</td>
				</tr>
				<tr style="background: none;" id="IdNumber">
					<td style="border:none;">
						<?php echo $this->Form->input('id_number', array('label' => 'Study Number', 'maxlength' => 7)); ?>
					</td>
					<td style="border:none;vertical-align: middle;">
						<a href="#" class="tooltips btn btn-inverse btn-mini" title="This is only for DTU students with validate studynumbers. Ex. s120124">
							<i class="icon-info-sign icon-large"></i>
						</a>
					</td>
				</tr>
			</tbody>
		</table>

	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>
