<div>
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('Edit LAN', array('action' => 'edit', $lan['Lan']['id'])); ?>
		</div>
	<?php endif; ?>
		<table style="width:500px;">
			<tbody>
				<tr style="font-size:110%">
					<td>Price:</td>
					<td><?php echo $lan['Lan']['price']; ?> DKK</td>
				</tr>
				<tr>
					<td>Date start:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_start']); ?></td>
				</tr>
				<tr>
					<td>Date end:</td>
					<td><?php echo $this->Time->nice($lan['Lan']['time_end']); ?></td>
				</tr>
				<tr>
					<td>Participants:</td>
					<td><?php echo ($count_lan_signups - $count_lan_signups_guests) . 's + ' . $count_lan_signups_guests . 'g = ' . $count_lan_signups; ?></td>
				</tr>

				<?php if ($is_admin): ?>
					<tr>
						<td rowspan="1" colspan="2"></td>
					</tr>
					<tr>
						<th colspan="2">Crew only</th>
					</tr>
					<tr>
						<td>Tournaments:</td>
						<td><?php echo $count_tournaments; ?></td>
					</tr>
					<tr>
						<td>Public:</td>
						<td><?php echo $lan['Lan']['published'] ? '<span class="text-bold text-green">Yes</span>' : '<span class="text-bold text-grey">No</span>'; ?></td>
					</tr>
					<tr>
						<td>Sign up open:</td>
						<td><?php echo $lan['Lan']['sign_up_open'] ? '<span class="text-bold text-green">Yes</span>' : '<span class="text-bold text-grey">No</span>'; ?></td>
					</tr>
					<tr>
						<td>Guests:</td>
						<td><?php echo $count_lan_signups === 0 ? 0 : floor($count_lan_signups_guests / $count_lan_signups * 100) ?> %</td>
					</tr>
					<tr>
						<td>Students:</td>
						<td><?php echo $count_lan_signups_guests === 0 ? 0 : floor(($count_lan_signups - $count_lan_signups_guests) / $count_lan_signups * 100) ?> %</td>
					</tr>
					<?php $total_signup = 0; ?>
					<?php $max_signup = 0; ?>
					<tr>
						<td>Fill rate:</td>
						<td><?php echo $max_signup === 0 ? 0 : floor(($total_signup / $max_signup) * 100) ?> %</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
</div>