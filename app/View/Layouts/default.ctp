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
				  'keywords', 'DTU LAN, DTU, LAN, Net-party, Net, Party, E2012, F2013, event, Tournament, League of Legends, Counter-Strike, Pizza, StarCraft 2, NetCompany, RedBull, SteelSeries, S/M-rådet'
		);

		echo $this->Html->meta(
				  'description', 'DTU LAN | Two events every year | Next event: F2013 | March 23rd - 27th 2013'
		);

		echo $this->Html->meta(
				  'favicon.ico', '/favicon.ico', array('type' => 'icon')
		);

		echo $this->Html->css(array(
			 'bootstrap/bootstrap.min',
			 'bootstrap/bootstrap-modification',
			 'font-awesome/font-awesome.min',
			 'ui-darkness/jquery-ui-1.10.0.custom',
//			 'jquery/jquery.fancybox',
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
					<p>DTU LAN &bull; Copyright &copy; 2012-<?php echo date('Y'); ?> &bull; <?php echo $this->Html->link('<i class="icon-envelope-alt"></i> Contact', 'mailto:contact@dtu-lan.dk', array('escape' => false)); ?></p>
				</div>
			</div>
		</div>

		<?php // echo $this->element('sql_dump'); ?>

		<?php
		echo $this->Html->script(array(
			 'jquery/jquery',
			 'jquery/jquery-ui',
			 'd3/d3.v3.min',
//			 'jquery/jquery.fancybox.pack',
			 'bootstrap/bootstrap.min',
			 'general',
				  ), true);
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache' => TRUE));
		?>

		<script type='text/javascript'>
			window.Muscula = { settings:{
					logId:"1b705fe3-263d-4809-919c-86fb44df5125", suppressErrors: false, branding: 'none'
				}};
			(function () {
				var m = document.createElement('script'); m.type = 'text/javascript'; m.async = true;
				m.src = (window.location.protocol == 'https:' ? 'https:' : 'http:') +
					'//musculahq.appspot.com/Muscula.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(m, s);
				window.Muscula.run=function(c){eval(c);window.Muscula.run=function(){};};
				window.Muscula.errors=[];window.onerror=function(){window.Muscula.errors.push(arguments);
					return window.Muscula.settings.suppressErrors===undefined;}
			})();
		</script>

	</body>
</html>
