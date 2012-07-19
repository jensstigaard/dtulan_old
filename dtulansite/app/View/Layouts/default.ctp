<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $cakeDescription ?>:
			<?php echo $title_for_layout; ?>
		</title>
		<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<h1><?php echo $this->Html->link('DTU LAN site', '../'); ?></h1>
			</div>
			<div id="content">

				<?php echo $this->Session->flash(); ?>
				<?php echo $this->Session->flash('auth'); ?>

				<?php echo $this->fetch('content'); ?>

				<div class="actions">
					<div style="margin-bottom:5px;">
						<?php
						if ($logged_in):
							?>
							<p>Welcome <?php echo $current_user['name']; ?>.</p>
							<ul style="margin-bottom: 30px;">


								<li><?php echo $this->Html->link('Profile', array('controller' => 'users', 'action' => 'profile')); ?></li>
								<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
								<?php if ($is_admin): ?>
							</ul>
							<ul>
									<li><?php echo $this->Html->link('Admin panel', array('controller' => 'admin', 'action' => 'panel')); ?></li>
									<li><?php echo $this->Html->link('Lans', array('controller' => 'lans', 'action' => 'index')); ?></li>
									<li><?php echo $this->Html->link('LAN signups', array('controller' => 'lansignups', 'action' => 'index')); ?></li>
									<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index')); ?></li>
									<li><?php echo $this->Html->link('Pages', array('controller' => 'pages', 'action' => 'index')); ?></li>
									<li><?php echo $this->Html->link('Pizzas', array('controller' => 'pizzas', 'action' => 'index')); ?></li>
								<?php endif; ?>
							</ul>

						<?php else: ?>
							<ul><li><?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?></li></ul>
						<?php endif; ?>

					</div>

				</div>
				<div id="footer">
					<?php
					echo $this->Html->link(
							$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')), 'http://www.cakephp.org/', array('target' => '_blank', 'escape' => false)
					);
					?>
				</div>
			</div>

			<?php // echo $this->element('sql_dump'); ?>

<!--		<pre><?php // print_r($current_user);        ?></pre>-->

	</body>
</html>
