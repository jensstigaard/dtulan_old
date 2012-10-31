<div>
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('Edit LAN', array('action' => 'edit', $lan['Lan']['id'])); ?>
		</div>
	<?php endif; ?>
	<h1><?php echo $lan['Lan']['title']; ?></h1>

	<div class="tabs">
		<ul>
			<li><a href="<?php
	echo $this->Html->url(array(
		'action' => 'view_general',
		$lan['Lan']['id']
	));
	?>"><?php echo $this->Html->image('24x24_PNG/001_40.png'); ?></a></li>

			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_crew',
					   $lan['Lan']['id']
				   ));
	?>"><?php echo $this->Html->image('24x24_PNG/crew.png'); ?></a></li>

			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_participants',
					   $lan['Lan']['id']
				   ));
	?>"><?php echo $this->Html->image('24x24_PNG/participants.png'); ?></a></li>

			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_tournaments',
					   $lan['Lan']['id']
				   ));
	?>"><?php echo $this->Html->image('24x24_PNG/trophy_gold.png'); ?></a></li>


			<?php if ($is_admin): ?>

				<li><a href="<?php
			echo $this->Html->url(array(
				'action' => 'view_pizzamenus',
				$lan['Lan']['id']
			));
				?>"><?php echo $this->Html->image('24x24_PNG/pizza.png'); ?></a></li>

				<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_invites',
					   $lan['Lan']['id']
				   ));
				?>"><?php echo $this->Html->image('24x24_PNG/001_13.png'); ?></a></li>

				<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_economics',
					   $lan['Lan']['id']
				   ));
				?>"><?php echo $this->Html->image('24x24_PNG/payment_cash.png'); ?></a></li>

			<?php endif; ?>

				<?php if($signup_available): ?>
				<li><a href="<?php
				   echo $this->Html->url(array(
					   'controller' => 'lan_signups',
					   'action' => 'add',
					   $lan['Lan']['id']
				   ));
				?>" title="Sign up now!"><?php echo $this->Html->image('24x24_PNG/001_01.png'); ?></a></li>
				<?php endif; ?>
		</ul>

		<div style="text-align:center;">
			<?php echo $this->Html->image('ajax-loader.gif', array('class' => 'hidden', 'id' => 'loading_indicator', 'alt' => 'loading ...')); ?>
		</div>
	</div>

</div>

<?php if (isset($user_guests) && count($user_guests)): ?>
	<div>
		<h2><?php echo $this->Html->image('32x32_PNG/user_add.png'); ?> Invite guest</h2>
		<?php echo $this->Form->create('LanInvite'); ?>
		<?php echo $this->Form->input('user_guest_id', array('label' => 'Invite guest to LAN', 'options' => $user_guests)); ?>
		<?php echo $this->Form->end('Invite'); ?>
	</div>
<?php endif; ?>


<?php if (isset($is_not_attending) && $is_not_attending): ?>
	<div>
		<h2><?php echo $this->Html->image('24x24_PNG/001_01.png'); ?> Sign up now!</h2>
		<p>You are not anticipating to this LAN.</p>
		<?php echo $this->Html->link('Sign up this LAN!', array('controller' => 'lan_signups', 'action' => 'add', $lan['Lan']['id'])); ?>
	</div>
<?php endif; ?>