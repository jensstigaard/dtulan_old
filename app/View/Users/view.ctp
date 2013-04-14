<?php echo $this->Html->css(array('users/profile'), null, array('inline' => false)); ?>
<div class="box">
	<h1>
		<?php
		if (!empty($user['User']['email_gravatar'])) {
			echo $this->Html->image(
					  'http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=64&amp;r=r', array('style' => 'width:64px;height:64px;margin-right:10px;'));
		}
		echo $user['User']['name'];
		?>
	</h1>

	<?php if (isset($make_payment_crew_id)): ?>
		<div class="box_inline" id="box_make_payment">
			<h3>New payment</h3>
			<?php
			echo $this->Form->create('Payment', array('controller' => 'payments', 'action' => 'add'));
			echo $this->Form->inputs(array(
				 'fieldset' => false,
				 'amount',
				 'user_id' => array(
					  'value' => $user['User']['id'],
					  'type' => 'hidden'
				 ),
				 'crew_id' => array(
					  'value' => $make_payment_crew_id,
					  'type' => 'hidden'
				 )
					  )
			);
			?>

			<p>Payment in: <strong><?php echo $make_payment_lan_title; ?></strong></p>
			<?php
			echo $this->Form->end(array(
				 'label' => __('Submit'),
				 'div' => array(
					  'style' => 'margin:0;padding:0 0 5px'
				 )
			));
			?>
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
						<td>
							<?php echo $user['User']['balance']; ?>
							<!--
							<?php if ($user['User']['balance'] > 0): ?>
								<?php
								echo $this->Html->link(
										  'Pay out', '#', array(
									 'class' => 'btn btn-mini btn-primary',
									 'style' => 'float:right;'
								));
								?>
							<?php endif; ?>
							-->
						</td>
					</tr>
				<?php endif; ?>
				<?php if ($is_admin && isset($user['QrCode']['id'])): ?>
					<tr>
						<td>Qr-code attached:</td>
						<td><img src="http://qrfree.kaywa.com/?l=1&s=4&d=<?php echo $user['QrCode']['id']; ?>" alt="QRCode" /></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<?php if (isset($tournaments_won['all']) && $tournaments_won['all']): ?>
			<h3>Tournament-wins</h3>
			<table id="user-tournaments-won">
				<tbody>
					<tr>
						<td>Gold</td>
						<td>Silver</td>
						<td>Bronze<td>
					</tr>
					<tr>
						<td><i class="icon-trophy" style="color:gold;font-size:20pt;"></i></td>
						<td><i class="icon-trophy" style="color:silver;font-size:20pt;"></i></td>
						<td><i class="icon-trophy" style="color:chocolate;font-size:20pt;"></i></td>
					</tr>
					<tr class="count">
						<td><?php echo $tournaments_won[1]; ?></td>
						<td><?php echo $tournaments_won[2]; ?></td>
						<td><?php echo $tournaments_won[3]; ?></td>
					</tr>
				</tbody>
			</table>

		<?php endif; ?>

	</div>
	<div style="clear:both"></div>
</div>

<div class="box">
	<div class="tabs">
		<ul>
			<?php if ($is_auth): ?>
				<li><a href="<?php
			echo $this->Html->url(array(
				 'action' => 'view_pizzaorders',
				 $user['User']['id']
			));
				?>"><i class="icon-food"></i></a></li>

				<li><a href="<?php
					 echo $this->Html->url(array(
						  'action' => 'view_foodorders',
						  $user['User']['id']
					 ));
				?>"><i class="icon-coffee"></i></a></li>

				<li><a href="<?php
					 echo $this->Html->url(array(
						  'action' => 'view_payments',
						  $user['User']['id']
					 ));
				?>"><i class="icon-money"></i></a></li>

			<?php endif; ?>

			<li><a href="<?php
			echo $this->Html->url(array(
				 'action' => 'view_tournaments',
				 $user['User']['id']
			));
			?>"><i class="icon-trophy"></i></a></li>

			<li><a href="<?php
					 echo $this->Html->url(array(
						  'action' => 'view_lans',
						  $user['User']['id']
					 ));
			?>"><i class="icon-sitemap"></i></a></li>

		</ul>

		<div class="loading_indicator">
			<i class="icon-spinner icon-spin icon-2x"></i>
		</div>
	</div>
</div>


<?php if ($user['User']['activated'] != 1 && $is_admin): ?>
	<div class="message">
		This user is not activated!
	</div>
<?php endif; ?>