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
