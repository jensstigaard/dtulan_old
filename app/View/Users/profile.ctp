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
			<?php if ($is_auth): ?>
				<li><a href="<?php
			echo $this->Html->url(array(
				'action' => 'view_pizzaorders',
				$user['User']['id']
			));
				?>"><?php echo $this->Html->image('24x24_PNG/pizza.png'); ?></a></li>

				<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_foodorders',
					   $user['User']['id']
				   ));
				?>"><?php echo $this->Html->image('24x24_PNG/candy.png'); ?></a></li>

				<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_payments',
					   $user['User']['id']
				   ));
				?>"><?php echo $this->Html->image('24x24_PNG/payment_cash.png'); ?></a></li>

			<?php endif; ?>

			<li><a href="<?php
			echo $this->Html->url(array(
				'action' => 'view_tournaments',
				$user['User']['id']
			));
			?>"><?php echo $this->Html->image('24x24_PNG/trophy_gold.png'); ?></a></li>

			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_lans',
					   $user['User']['id']
				   ));
			?>"><?php echo $this->Html->image('24x24_PNG/games.png'); ?></a></li>

		</ul>

		<div style="text-align:center;">
			<?php echo $this->Html->image('ajax-loader.gif', array('class' => 'hidden', 'id' => 'loading_indicator', 'alt' => 'loading ...')); ?>
		</div>
	</div>
</div>


<?php if ($user['User']['activated'] != 1 && $is_admin): ?>
	<div class="message">
		This user is not activated!
	</div>
<?php endif; ?>
