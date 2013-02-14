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
				<table>
					<tr>
						<td>							
							<?php echo $this->Html->image('logo_f2013_black.png'); ?>
						</td>

						<td>
							<div class="price">
								<?php echo $lan['Lan']['price']; ?> DKK
							</div>
							<p>March 23rd - 27th 2013</p>
							<p class="text">Code for sign-up: (one-time use)</p>
							<h1 class="code"><?php echo $code['LanSignupCode']['code']; ?></h1>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding-top:10px;text-align:center">http://dtu-lan.dk/lan_signup/<?php echo $lan['Lan']['slug']; ?></td>
					</tr>
				</table>

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