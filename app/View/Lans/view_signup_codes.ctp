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

				<?php
				echo $this->Html->image('logo_black.png', array(
					 'style' => 'width: 7cm; display:block; margin: 0.4cm auto 0.2cm;'
				));
				?>
				<div class="ticket_content">
					<h2>http://dtu-lan.dk/lan_signup/f2013</h2>
					<p>Code for sign-up (one-time use)</p>
					<h1><?php echo $code['LanSignupCode']['code']; ?></h1>
				</div>

				<div class="corner"></div>
			</div>
			<?php if ($i % $settings['columns'] == $settings['columns'] - 1 || $i + 1 == count($codes)): ?>
			</div>
		<?php endif; ?>
		<?php if ($i % ($settings['rows_per_page'] * $settings['columns']) == ($settings['rows_per_page'] * $settings['columns']) - 1 || $i + 1 == count($codes)) : ?>
		</div>
		<div style="clear:both; page-break-after: always;"></div>
	<?php endif; ?>
<?php endforeach; ?>