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
			 'bootstrap/bootstrap.min',
			 'font-awesome/font-awesome.min',
			 'ui-darkness/jquery-ui-1.10.0.custom',
//			 'jquery/jquery.qtip.min',
			 'jquery/jquery.fancybox',
			 'normalize',
			 'layout.general',
			 'layout.tables',
			 'layout.navigation',
			 'sidebar',
			 'cake.errors',
				  )
		);

		if ($is_admin) {
			echo $this->Html->css(array(
				 'admin',
			));
		}

		if ($is_admin) {
			$this->Html->script(array('admin/user_lookup'), false);
		}

		echo $this->fetch('meta');
		echo $this->fetch('css');
		?>
	</head>
	<body>
		<div id="header">
			<div>
				<?php echo $this->Html->image('logo.png', array('url' => '/')); ?>
				<nav>
					<?php echo $this->element('menu'); ?>
				</nav>
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
				</div>
				<div id="copyright">
					<p>DTU LAN Party &bull; Copyright &copy; 2012 &bull; <?php echo $this->Html->link('<i class="icon-envelope-alt"></i> Contact', 'mailto:contact@dtu-lan.dk', array('escape'=>false)); ?></p>
				</div>
			</div>
		</div>

		<?php // echo $this->element('sql_dump');      ?>

		<?php
		echo $this->Html->script(array(
			 'jquery/jquery',
			 'jquery/jquery-ui',
			 'jquery/jquery.qtip.min',
			 'jquery/jquery.fancybox.pack',
			 'jquery/jquery.fittext',
			 'jquery/jquery.masonry.min',
//			 'jquery/jquery.isotope.min',
			 'bootstrap/bootstrap.min',
			 'general',
				  ), true);
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache' => TRUE));
		?>
	</body>
</html>
