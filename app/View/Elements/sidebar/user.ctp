<div>
	<?php
	if (!empty($current_user['email_gravatar'])) {
		$size = 72;
		echo $this->Html->image(
				'http://www.gravatar.com/avatar/' . md5(strtolower($current_user['email_gravatar'])) . '?s='.$size.'&amp;r=r', array(
			'style' => 'float:left;margin: 5px;width:'.$size.'px;height:'.$size.'px;'
				)
		);
	}
	?>
	<div style="margin-left: 80px;">
		<h2 style="padding-bottom:0;">
			<?php echo $this->Html->link($current_user['name'], array('controller' => 'users', 'action' => 'profile')); ?>
		</h2>
		<div>
			<p>Balance:
				<?php
				$color = '';

				if ($current_user_balance < 0) {
					$color = 'color: #FF0000;';
				}
				elseif($current_user_balance > 0) {
					$color = 'color: #00CC00;';
				}
				?>
				<strong style="<?php echo $color; ?>">
					<?php echo $current_user_balance; ?>
				</strong>
			</p>
		</div>
	</div>

	<div style="clear:both;"></div>

	<ul>
		<li><?php echo $this->Html->link($this->Html->image('16x16_PNG/spanner.png', array('alt' => 'Edit info')) . ' Edit personal data', array('controller' => 'users', 'action' => 'edit'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link($this->Html->image('16x16_GIF/login.gif', array('alt' => 'Log out')) . ' Logout', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?></li>
	</ul>
</div>
