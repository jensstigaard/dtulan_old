<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			DTU LAN &bull;
			<?php echo $title_for_layout; ?>
		</title>
		<?php
		echo $this->Html->meta(
				'keywords', 'DTU LAN Party, DTU, LAN, Party, E2012, event, Tournament, League of Legends, Counter-Strike, Pizza, StarCraft 2, NetCompany, RedBull, SteelSeries, S/M-rådet'
		);

		echo $this->Html->meta(
				'description', 'DTU LAN Party | Two LAN-events every year | Next event: E2012 | October 13th - 18th 2012'
		);

		echo $this->Html->meta(
				'favicon.ico', '/favicon.ico', array('type' => 'icon')
		);

		echo $this->Html->css(array(
			'normalize',
			'layout.general',
			'layout.tables',
			'layout.menu',
			'layout.sidebar',
			'cake.errors',
			'ui-darkness/jquery-ui'
				)
		);

		if ($is_admin) {
			echo $this->Html->css(array(
				'layout.admin'));
		}

		if ($is_admin) {
			$this->Html->script(array('admin/user_lookup'), false);
		}

		echo $this->fetch('meta');
		echo $this->fetch('css');

		echo $this->Html->script(array('jquery', 'jquery-ui', 'generel'), true);
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache' => TRUE));
		?>
	</head>
	<body>
		<div id="header">
			<div>
				<?php echo $this->Html->image('logo.png', array('url' => '/')); ?>
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
						<p>DTU LAN Party &bull; Copyright &copy; 2012 &bull; <?php echo $this->Html->link('Contact', 'mailto:contact@dtu-lan.dk'); ?></p>
					</div>
				</div>
			</div>
		</div>

		<?php // echo $this->element('sql_dump');    ?>
	</body>
</html>
