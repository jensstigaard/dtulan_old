<div>
	<?php if (!count($lans)): ?>
		<p>Not signed up for any LANs</p>
	<?php else: ?>
		<table>
			<tr>
				<th>Title</th>
			</tr>


			<?php foreach ($lans as $lan): ?>
				<?php if ($is_auth || $lan['Lan']['published']): ?>
					<tr>
						<td>
							<?php
							echo $this->Html->link($lan['Lan']['title'], array(
								 'controller' => 'lans',
								 'action' => 'view',
								 'slug' => $lan['Lan']['slug']
							));
							?>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>