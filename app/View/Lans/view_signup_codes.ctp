<div>
	<table>
		<?php foreach ($codes as $code): ?>
			<tr>
				<td>#<?php echo $code['LanSignupCode']['id']; ?></td>
				<td><?php echo $code['LanSignupCode']['code']; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
