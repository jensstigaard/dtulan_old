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
			'cake.errors',
			'ui-darkness/jquery-ui'
				)
		);

		if ($logged_in && $is_admin) {
			echo $this->Html->css(array(
				'layout.admin'));
		}

		$this->Html->script(array('jquery', 'jquery-ui', 'generel'), false);

		if ($is_admin) {
			$this->Html->script(array('admin/user_lookup'), false);
		}

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache' => TRUE));
		?>
	</head>
	<body>
		<div id="header">
			<div>
				<?php echo $this->Html->image('dtulan_logo.png', array('url' => '../')); ?>
				<div class="menu">
					<?php echo $this->element('menu'); ?>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="content">

				<div class="content">
					<?php
					echo $this->Session->flash();
					echo $this->Session->flash('good');
					echo $this->Session->flash('bad');
					echo $this->Session->flash('auth');
					echo $this->fetch('content');
					?>
				</div>

				<div id="sidebar">
					<?php echo $this->element('sidebar', array()); ?>
				</div>
			</div>
		</div>
		<div id="footer">
			<div>
				<div id="sponsors">
					<?php echo $this->element('sponsors', array()); ?>
					<div id="copyright">
						<p>DTU LAN Party &bull; Copyright &copy 2012 &bull; <?php echo $this->Html->link('contact@dtu-lan.dk', 'mailto: contact@dtu-lan.dk'); ?></p>
					</div>
				</div>
			</div>
		</div>

		<?php // echo $this->element('sql_dump'); ?>
	</body>
</html>
