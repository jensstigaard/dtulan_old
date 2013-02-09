<?php if (isset($sidebar_new_lan['Lan']['title'])): ?>
	<div class="unsigned_lan">
		<h1><?php echo $this->Html->image('24x24_PNG/001_15.png'); ?> New event!</h1>
		<h2><?php
	echo $this->Html->link($sidebar_new_lan['Lan']['title'], array(
		 'controller' => 'lans',
		 'action' => 'view',
		 'slug' => $sidebar_new_lan['Lan']['slug']
			  )
	);
	?></h2>
		<p>
			Start: <?php echo $this->Time->nice($sidebar_new_lan['Lan']['time_start']); ?><br />
			End: <?php echo $this->Time->nice($sidebar_new_lan['Lan']['time_end']); ?>
		</p>
		<ul>
			<li><?php
		echo $this->Html->link(
				  $this->Html->image('16x16_GIF/action_check.gif') . ' Sign up now!', array('controller' => 'lan_signups', 'action' => 'add', $sidebar_new_lan['Lan']['slug']), array('escape' => false));
	?></li>
		</ul>
	</div>
<?php endif; ?>

<?php if (count($sidebar_team_invites)): ?>
	<?php foreach ($sidebar_team_invites as $invite): ?>
		<div class="invite">
			<h1><?php echo $this->Html->image('24x24_PNG/001_15.png'); ?> Invite to team</h1>
			<p>You are invited to <strong><?php echo $invite['Team']['name']; ?></strong> by <strong><?php echo $invite['Team']['name']; ?></strong>!</p>
			<ul>
				<li>
					<?php
					echo $this->Html->link($this->Html->image('16x16_GIF/action_check.gif') . ' Join team', array(
						 'controller' => 'team_users',
						 'action' => 'add',
						 $invite['TeamInvite']['id']), array('escape' => false)
					);
					?>
				</li>
				<li>
					<?php
					echo $this->Form->postLink($this->Html->image('16x16_GIF/action_delete.gif') . ' Decline invite', array(
						 'controller' => 'team_invites',
						 'action' => 'delete',
						 $invite['TeamInvite']['id']), array(
						 'confirm' => 'Do you want to decline this invite?',
						 'escape' => false
							  )
					);
					?>
				</li>
			</ul>
		</div>
	<?php endforeach; ?>
<?php endif; ?>