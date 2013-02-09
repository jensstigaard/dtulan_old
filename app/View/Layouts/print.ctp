<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			DTU LAN &bull;
			<?php echo $title_for_layout; ?>
		</title>
		<?php
		echo $this->Html->css(array(
			 'print'
		));
		echo $this->fetch('css');
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
