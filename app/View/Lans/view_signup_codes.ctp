<?php
echo $this->Html->css('signup_codes', null, array('inline' => false));
?>

<?php foreach ($codes as $i => $code): ?>
	<?php if ($i % ($settings['rows_per_page'] * $settings['columns']) == 0) : ?>
		<div class="page">
		<?php endif; ?>
		<?php if ($i % $settings['columns'] == 0) : ?>
			<div class="line">
			<?php endif; ?>

			<div class="signup_ticket">
				<?php echo $this->Html->image('logos/logo_black_big.png'); ?>

				<div class="ticket_content">
					<h2 class="event_info"><?php echo $lan['Lan']['title']; ?> &bull; March 23rd - 27th 2013</h2>
					<p class="text">Code for sign-up: (one-time use)</p>
					<h1 class="code"><?php echo $code['LanSignupCode']['code']; ?></h1>
					<p class="url">http://dtu-lan.dk/lan_signup/<?php echo $lan['Lan']['slug']; ?></p>
				</div>

				<div class="corner"></div>
			</div>
		<?php if ($i % $settings['columns'] == $settings['columns'] - 1 || $i + 1 == count($codes)): ?>
			</div>
		<?php endif; ?>
	<?php if ($i % ($settings['rows_per_page'] * $settings['columns']) == ($settings['rows_per_page'] * $settings['columns']) - 1 || $i + 1 == count($codes)) : ?>
		</div>
		<div class="pagebreak"></div>
	<?php endif; ?>
<?php endforeach; ?>