<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			DTU LAN site &bull;
			<?php echo $title_for_layout; ?>
		</title>
		<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css(array(
			'layout.general',
			'layout.menu',
			'cake.errors'));

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache'=>TRUE));
		?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<h1><?php echo $this->Html->link('DTU LAN site', '../'); ?></h1>
				<div class="menu">
					<?php echo $this->element('menu'); ?>
				</div>
			</div>
			<div id="content">

				<?php echo $this->Session->flash(); ?>
				<?php echo $this->Session->flash('auth'); ?>

				<?php echo $this->fetch('content'); ?>

				<div class="actions">
					<?php echo $this->element('sidebar', array()); ?>
				</div>
				<div id="footer">
					<?php
					echo $this->Html->link(
							$this->Html->image('cake.power.gif', array('alt' => 'Hehe', 'border' => '0')), 'http://www.cakephp.org/', array('target' => '_blank', 'escape' => false)
					);
					?>
				</div>
			</div>

			<?php // echo $this->element('sql_dump'); ?>

<!--		<pre><?php // print_r($current_user);          ?></pre>-->
		</div>
	</body>
</html>
