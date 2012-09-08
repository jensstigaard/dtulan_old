<!DOCTYPE html>
<html lang="en">
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
			'layout.tables',
			'cake.errors'
				)
		);

		if ($logged_in && $is_admin) {
			echo $this->Html->css(array(
				'layout.admin'));
		}

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache' => TRUE));
		?>
	</head>
	<body>
		<div id="header">
			<h1><?php echo $this->Html->link('DTU LAN site', '../'); ?></h1>
			<div class="menu">
				<?php echo $this->element('menu'); ?>
			</div>
		</div>
		<div id="container">
			<div id="content">

				<div>
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Session->flash('auth'); ?>
				</div>

				<div class="content">
					<?php echo $this->fetch('content'); ?>
				</div>

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

<!--		<pre><?php // print_r($current_user);               ?></pre>-->
		</div>
	</body>
</html>
