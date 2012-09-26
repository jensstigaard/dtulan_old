<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			DTU LAN site &bull;
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

		$this->Html->script(array('jquery', 'jquery-ui', 'generel'), false);

		if ($is_admin) {
			$this->Html->script(array('admin/user_lookup'), false);
		}

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Js->writeBuffer(array('cache' => TRUE));
		?>

		<style type="text/css">
			.breakhere{
				page-break-before: always;
			}
		</style>
	</head>
	<body>

		<?php
		echo $this->Session->flash();
		echo $this->Session->flash('good');
		echo $this->Session->flash('bad');
		echo $this->Session->flash('auth');
		echo $this->fetch('content');
		?>

	</body>
</html>
