<div>
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('Edit tournament', array('action' => 'edit', $tournament['Tournament']['id'])); ?>
		</div>
	<?php endif; ?>

	<h1><?php echo $tournament['Tournament']['title']; ?></h1>
	<p>
		In LAN: <?php
	echo $this->Html->link($lan['Lan']['title'], array(
		 'controller' => 'lans',
		 'action' => 'view', $lan['Lan']['slug']
			  )
	);
	?>
	</p>
	<table>
		<tbody>
			<tr>
				<td>Team size:</td>
				<td><?php echo $tournament['Tournament']['team_size']; ?> persons</td>
			</tr>
			<tr>
				<td>Start time:</td>
				<td>
					<?php echo $tournament['Tournament']['time_start_nice']; ?>

				</td>
			</tr>
		</tbody>
	</table>
</div>

<div>
	<div class="tabs">
		<ul>
			<li><a href="<?php
					echo $this->Html->url(array(
						 'action' => 'view_description',
						 $lan['Lan']['slug'],
						 $tournament['Tournament']['slug']
					));
					?>"><i class="icon-info-sign"></i></a></li>
			<li><a href="<?php
				echo $this->Html->url(array(
					 'action' => 'view_rules',
					 $lan['Lan']['slug'],
					 $tournament['Tournament']['slug']
				));
					?>"><i class="icon-book"></i></a></li>
			<li><a href="<?php
				echo $this->Html->url(array(
					 'action' => 'view_teams',
					 $lan['Lan']['slug'],
					 $tournament['Tournament']['slug']
				));
					?>"><i class="icon-group"></i></a></li>
			<li><a href="<?php
				echo $this->Html->url(array(
					 'action' => 'view_bracket',
					 $lan['Lan']['slug'],
					 $tournament['Tournament']['slug']
				));
					?>"><i class="icon-sitemap"></i></a></li>
		</ul>

		<div class="loading_indicator">
			<i class="icon-spinner icon-spin icon-2x"></i>
		</div>
	</div>
</div>