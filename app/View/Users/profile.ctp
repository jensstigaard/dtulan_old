<?php echo $this->Html->script('users/profile', FALSE); ?>

<div>
	<h1>
		<?php
		if (!empty($user['User']['email_gravatar'])) {
			echo $this->Html->image(
					'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=64&amp;r=r', array('style' => 'margin-right:10px;'));
		}
		echo $user['User']['name'];
		?>
	</h1>

	<?php if (isset($make_payment_crew_id)): ?>
		<div style="float:right;width:200px;background-color:rgba(0,0,0,.2);padding:10px;">
			<?php echo $this->Form->create('Payment', array('controller' => 'payments', 'action' => 'add')); ?>
			<?php echo $this->Form->input('amount', array('label' => 'Make payment')); ?>
			<?php echo $this->Form->hidden('user_id', array('value' => $user['User']['id'])); ?>
			<?php echo $this->Form->hidden('crew_id', array('value' => $make_payment_crew_id)); ?>
			<?php echo $this->Form->end(__('Submit')); ?>
		</div>
	<?php endif; ?>

	<div>
		<table style="width:400px;clear:none;">
			<tbody>
				<tr>
					<td>Gamertag:</td>
					<td><?php echo $user['User']['gamertag']; ?></td>
				</tr>
				<?php if ($is_auth): ?>
					<tr>
						<td>Email:</td>
						<td><?php echo $user['User']['email']; ?></td>
					</tr>
					<tr>
						<td>Type:</td>
						<td><?php echo $user['User']['type']; ?></td>
					</tr>
					<tr>
						<td>ID-number:</td>
						<td><?php echo $user['User']['id_number']; ?></td>
					</tr>
					<tr>
						<td>Phonenumber:</td>
						<td><?php echo $user['User']['phonenumber']; ?></td>
					</tr>
					<tr style="font-size:13pt;">
						<td>Balance:</td>
						<td><?php echo $user['User']['balance']; ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div style="clear:both"></div>
</div>

<div>
	<div class="tabs">
		<ul>
			<li><a href="#tab-pizzaorders"><?php echo $this->Html->image('24x24_PNG/pizza.png'); ?></a></li>
			<li><a href="#tab-payments"><?php echo $this->Html->image('24x24_PNG/payment_cash.png'); ?></a></li>
			<li><a href="#tab-foodorders"><?php echo $this->Html->image('24x24_PNG/candy.png'); ?></a></li>
                        <li><a href="#tab-tournaments"><?php echo $this->Html->image('24x24_PNG/trophy_gold.png'); ?></a></li>
                        <li><a href="#tab-lans"><?php echo $this->Html->image('24x24_PNG/games.png'); ?></a></li>
		</ul>

		<div id="tab-pizzaorders">
			<div class="pizza_orders">
				<?php echo $this->Html->image('misc/loading_indicator.gif'); ?>
				<?php echo $this->Html->link('', array('controller' => 'pizza_orders', 'action' => 'index', $user['User']['id'])); ?>
			</div>
		</div>

		<?php if ($is_auth): ?>
			<div id="tab-payments">
				<div class="payments">
					<?php echo $this->Html->image('misc/loading_indicator.gif'); ?>
					<?php echo $this->Html->link('', array('controller' => 'payments', 'action' => 'index_user', $user['User']['id'])); ?>
				</div>
			</div>

			<div id="tab-foodorders">
				<div class="food_orders">
					<?php echo $this->Html->image('misc/loading_indicator.gif'); ?>
					<?php echo $this->Html->link('', array('controller' => 'food_orders', 'action' => 'index_user', $user['User']['id'])); ?>
				</div>
			</div>
		<?php endif; ?>
            <div id="tab-tournaments">
                <?php if (!count($teams)): ?>
			<p>You do not participate in any tournament</p>
		<?php else: ?>
			<table>
				<tr>
					<th>Tournament</th>
					<th>Name</th>
					<th>Leader</th>
					<th>Members</th>
				</tr>

				<?php foreach ($teams as $team): ?>
					<tr>
						<td><?php echo $this->Html->link($team['Team']['Tournament']['title'], array('controller' => 'tournaments', 'action' => 'view', $team['Team']['Tournament']['id'])); ?></td>
						<td><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'view', $team['Team']['id'])); ?></td>
						<td><?php echo $team['TeamUser']['is_leader'] ? $this->Html->image('16x16_PNG/star.png') : ''; ?></td>
						<td><?php echo count($team['Team']['TeamUser']); ?> </td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
            </div>
            <div id="tab-lans">
                <?php if (!count($lans)): ?>
			<p>Not signed up for any LANs</p>
		<?php else: ?>
			<table>
				<tr>
					<th>Title</th>
					<th>Days attending</th>
					<?php if ($is_auth): ?>
						<th>Guests of you</th>
					<?php endif; ?>
				</tr>


				<?php foreach ($lans as $lan): ?>
					<?php if ($lan['Lan']['published'] || $is_auth): ?>
						<tr>
							<td>
								<?php echo $this->Html->link($lan['Lan']['title'], array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug'])); ?>

								<?php if ($is_you && $lan['Lan']['sign_up_open']): ?>
									<br />
									<?php
									echo $this->Html->link(
											$this->Html->image('16x16_GIF/reply.gif') . ' Edit your signup', array('controller' => 'lan_signups', 'action' => 'edit', $lan['Lan']['id']), array('escape' => false)
									);
									?>
								<?php endif; ?>
								<?php if (isset($lan['LanInvite']['Student'])): ?>
									<br />
									<small>Invited by: <?php echo $this->Html->link($lan['LanInvite']['Student']['name'], array('controller' => 'users', 'action' => 'profile', $lan['LanInvite']['Student']['id'])); ?></small>
								<?php endif; ?>
								<?php if ($is_auth && count($lan['Lan']['LanInvite'])): ?>

								<?php endif; ?>
							</td>
							<td>
								<?php foreach ($lan['LanSignupDay'] as $day): ?>
									<?php echo $this->Time->format('M jS (l)', $day['LanDay']['date']); ?><br />
								<?php endforeach; ?>
							</td>

							<?php if ($is_auth): ?>
								<td>
									<?php if (isset($lan_invites_accepted) && count($lan_invites_accepted)): ?>
										<?php foreach ($lan_invites_accepted[$lan['Lan']['id']] as $invite_accepted): ?>
											<?php echo $this->Html->link($invite_accepted['Guest']['name'], array('controller' => 'users', 'action' => 'profile', $invite_accepted['Guest']['id'])); ?><br />
										<?php endforeach; ?>
									<?php endif; ?>
								</td>
							<?php endif; ?>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
            </div>
	</div>
</div>


<?php if ($user['User']['activated'] != 1 && $is_admin): ?>
	<div class="message">
		This user is not activated!
	</div>
<?php endif; ?>
