<?php if (isset($next_lan['Lan']['title'])): ?>
	<div class="unsigned_lan">
		<h1><?php echo $this->Html->image('24x24_PNG/001_01.png'); ?> New event!</h1>
		<h2><?php echo $this->Html->link($next_lan['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $next_lan['Lan']['id'])); ?></h2>
		<p>
			Start: <?php echo $this->Time->nice($next_lan['Lan']['time_start']); ?><br />
			End: <?php echo $this->Time->nice($next_lan['Lan']['time_end']); ?>
		</p>
		<ul>
			<li><?php
	echo $this->Html->link(
			$this->Html->image('16x16_GIF/action_check.gif') . ' Sign up now!', array('controller' => 'lan_signups', 'action' => 'add', $next_lan['Lan']['id']), array('escape' => false));
	?></li>
		</ul>
	</div>
<?php endif; ?>

<?php if (isset($lan_invites['Lan']['title'])): ?>
	<div class="invite">
		<h1><?php echo $this->Html->image('24x24_PNG/001_01.png'); ?> Personal invite</h1>
		<p>You are invited to <strong><?php echo $lan_invites['Lan']['title']; ?></strong> by <strong><?php echo $lan_invites['Student']['name']; ?></strong>!</p>
		<ul>
			<li><?php
	echo $this->Html->link($this->Html->image('16x16_GIF/action_check.gif') . ' Accept and signup now', array(
		'controller' => 'lan_signups',
		'action' => 'add',
		$lan_invites['Lan']['id']), array('escape' => false)
	);
	?></li>
			<li><?php
			echo $this->Form->postLink($this->Html->image('16x16_GIF/action_delete.gif') . ' Decline invite', array(
				'controller' => 'lan_invites',
				'action' => 'decline',
				$lan_invites['LanInvite']['id']), array(
				'confirm' => 'Do you want to decline this invite?',
				'escape' => false
					)
			);
	?></li>
		</ul>
	</div>
<?php endif; ?>