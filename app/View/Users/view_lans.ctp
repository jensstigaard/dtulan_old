<div>
	<?php if (!count($lans)): ?>
		<p>Not signed up for any LANs</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Title</th>
				<?php if ($is_you): ?>
					<th></th>
				<?php endif; ?>
			</tr>


			<?php foreach ($lans as $lan): ?>
				<?php if ($lan['Lan']['published'] || $is_auth): ?>
					<tr>
						<td>
							<?php
							echo $this->Html->link($lan['Lan']['title'], array(
								 'controller' => 'lans',
								 'action' => 'view',
								 'slug' => $lan['Lan']['slug']
							));
							?>

							<?php if ($is_you): ?>
							<td>
								<?php
								if ($lan['Lan']['sign_up_open']) {
									echo $this->Form->postLink(
											  '<i class="icon-large icon-remove"></i> Delete your signup', array(
										 'controller' => 'lan_signups',
										 'action' => 'delete',
										 $lan['Lan']['slug']
											  ), array(
										 'confirm' => 'Are You sure you will delete the signup?',
										 'escape' => false,
										 'class' => 'btn btn-danger btn-mini'
											  )
									);
								}
								?>
							</td>
					<?php endif; ?>
						</td>
					</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</table>
<?php endif; ?>
</div>