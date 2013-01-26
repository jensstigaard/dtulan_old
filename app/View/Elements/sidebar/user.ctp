<div id="user_logged_in">
	<?php
	if (!empty($current_user['email_gravatar'])) {
		$size = 72;
		echo $this->Html->image(
				  'http://www.gravatar.com/avatar/' . md5(strtolower($current_user['email_gravatar'])) . '?s=' . $size . '&amp;r=r', array(
			 'style' => 'float:left;margin: 5px;width:' . $size . 'px;height:' . $size . 'px;'
				  )
		);
	}
	?>
	<div id="sidebar_user_info">
		<?php echo $this->Html->link($current_user['name'], array('controller' => 'users', 'action' => 'profile'), array('id' => 'user_name_link')); ?>
		<p id="user_balance">Balance:
			<?php
			$color = '';

			if ($current_user_balance < 0) {
				$color = 'color: #FF0000;';
			} elseif ($current_user_balance > 0) {
				$color = 'color: #00CC00;';
			}
			?>
			<strong style="<?php echo $color; ?>">
				<?php echo $current_user_balance; ?>
			</strong>
		</p>
	</div>

	<div style="clear:both;"></div>

	<ul>
		<li><?php echo $this->Html->link('<i class="icon-wrench icon-large"></i> Edit personal data', array('controller' => 'users', 'action' => 'edit'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('<i class="icon-lock icon-large"></i> Logout', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?></li>
	</ul>
</div>
