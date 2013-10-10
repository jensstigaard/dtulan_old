<div class="box">
	<h1>QR-codes</h1>

	<?php if (!count($qr_codes)): ?>
		<p>No QR-codes attached to any users</p>
	<?php else: ?>
		<div>
			<?php foreach ($qr_codes as $qr_code): ?>
				<div style="float:left;padding:14px 8px;width:140px;">
					<div>
						<img src="http://qrfree.kaywa.com/?l=1&s=4&d=<?php echo $qr_code['QrCode']['id']; ?>" alt="QRCode" />
					</div>
					<div>
						<?php
						echo $this->Html->image(
								  'http://www.gravatar.com/avatar/' . md5(strtolower($qr_code['User']['email_gravatar'])) . '?s=124&amp;r=r', array('style' => 'width:124px;height:124px;margin-right:10px;'));
						?>
					</div>
					<div style="height:45px;">
						<?php
						echo $this->Html->link($qr_code['User']['name'], array(
							 'controller' => 'users',
							 'action' => 'view',
							 $qr_code['User']['id']
						));
						?>
					</div>
					<div class="btn-group">
						<?php
						echo $this->Html->link('<i class="icon-remove icon-large"></i> Delete', array(
							 'controller' => 'qr_codes',
							 'action' => 'delete',
							 $qr_code['QrCode']['id']
								  ), array(
							 'class' => 'btn btn-mini btn-danger',
							 'escape' => false
						));
						echo $this->Html->link('<i class="icon-pencil icon-large"></i> Modify', array(
							 'controller' => 'qr_codes',
							 'action' => 'edit',
							 $qr_code['QrCode']['id']
								  ), array(
							 'class' => 'btn btn-mini btn-warning',
							 'escape' => false
						));
						?>
					</div>
				</div>
			<?php endforeach; ?>
			<div style="clear:both"></div>
			<?php
			echo $this->Paginator->numbers();
			?>

		</div>
	<?php endif; ?>
</div>