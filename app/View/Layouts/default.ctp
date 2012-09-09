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
			'layout.tables',
			'layout.menu',
			'layout.sidebar',
			'cake.errors'
				)
		);

		if ($logged_in && $is_admin) {
			echo $this->Html->css(array(
				'layout.admin'));
		}

		$this->Html->script(array('jquery', 'generel'), false);

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache' => TRUE));
		?>
	</head>
	<body>
		<div id="header">
			<?php echo $this->Html->image('dtulan_logo.png', array('url' => '../')); ?>
			<div class="menu">
				<?php echo $this->element('menu'); ?>
			</div>
		</div>
		<div id="container">
			<div id="content">

				<div class="content">
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->Session->flash('auth'); ?>
					<?php echo $this->fetch('content'); ?>
				</div>

				<div id="sidebar">
					<?php echo $this->element('sidebar', array()); ?>
					<div id="sponsors">
						<h1>Sponsors</h1>
						<ul>
							<li>
								<?php
								echo $this->Html->link(
										$this->Html->image('netcompany.png', array('alt' => 'NetCompany.com')), 'http://www.netcompany.com/', array('target' => '_blank', 'escape' => false)
								);
								?>
							</li>
							<li>
								<?php
								echo $this->Html->link(
										$this->Html->image('steelseries.png', array('alt' => 'SteelSeries')), 'http://www.steelseries.com/', array('target' => '_blank', 'escape' => false)
								);
								?>
							</li>
							<li>
								<?php
								echo $this->Html->link(
										$this->Html->image('fucapo.png', array('alt' => 'Fucapo - energityggegummi')), 'http://www.fucapo.com/', array('target' => '_blank', 'escape' => false)
								);
								?>
							</li>
						</ul>
					</div>
				</div>
				<div id="footer">

				</div>
			</div>

			<?php // echo $this->element('sql_dump'); ?>

<!--		<pre><?php // print_r($current_user);                   ?></pre>-->
		</div>
	</body>
</html>
