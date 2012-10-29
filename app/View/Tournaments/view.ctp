<?php echo $this->Html->script('tournament/view', FALSE); ?>

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
						$tournament['Tournament']['id']
					));
					?>"><?php echo $this->Html->image('24x24_PNG/001_50.png'); ?></a></li>
			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_rules',
					   $tournament['Tournament']['id']
				   ));
					?>"><?php echo $this->Html->image('24x24_PNG/001_34.png'); ?></a></li>
			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_teams',
					   $tournament['Tournament']['id']
				   ));
					?>"><?php echo $this->Html->image('24x24_PNG/001_57.png'); ?></a></li>
			<li><a href="<?php
				   echo $this->Html->url(array(
					   'action' => 'view_bracket',
					   $tournament['Tournament']['id']
				   ));
					?>"><?php echo $this->Html->image('24x24_PNG/001_44.png'); ?></a></li>
		</ul>
	</div>
</div>
<?php
// pr($tournament); ?>