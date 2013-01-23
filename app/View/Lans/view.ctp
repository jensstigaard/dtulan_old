<div>
	<?php if ($is_admin): ?>
		<div style="float:right">
			<?php echo $this->Html->link('Edit LAN', array('action' => 'edit', $lan['Lan']['id'])); ?>
		</div>
	<?php endif; ?>
	<h1><?php echo $title; ?></h1>

	<div id="lan_overview">
		<div>
			<div class="lan_overview_item" title="Date">
				<i class="icon-calendar"></i>
				<span><?php echo $this->Time->format('d/m/y H:i', $lan['Lan']['time_start']) . ' - ' . $this->Time->format('d/m/y H:i', $lan['Lan']['time_end']); ?></span>
			</div>
			<div class="lan_overview_item" title="Price">
				<i class="icon-money"></i>
				<span><?php echo $lan['Lan']['price']; ?> DKK</span>

			</div>
			<div class="lan_overview_item" title="Participants">
				<i class="icon-group"></i>
				<span><?php echo $data['count_signups']; ?> (<?php echo $lan['Lan']['max_participants']; ?>)</span>
			</div>
			<div class="lan_overview_item" title="Fill rate">
				<i class="icon-dashboard"></i>
				<span><?php echo $data['fill_rate']; ?> %</span>
			</div>
		</div>

		<div>
			<div class="lan_overview_item" title="Tournaments">
				<i class="icon-trophy"></i>
				<span><?php echo $data['count_tournaments']; ?></span>
			</div>

			<div class="lan_overview_item" title="Sign up open">
				<?php if ($lan['Lan']['sign_up_open']): ?>
					<i class="icon-plus-sign"></i>
					<span>Signup open</span>
				<?php else: ?>
					<i class="icon-ban-circle"></i>
					<span>Signup not open</span>
				<?php endif; ?>
			</div>

			<?php if ($is_admin): ?>

				<div class="lan_overview_item" title="Published">
					<?php if ($lan['Lan']['published']): ?>
						<i class="icon-ok-sign"></i>
						<span>Published</span>
					<?php else: ?>
						<i class="icon-minus-sign"></i>
						<span>Unpublished</span>
					<?php endif; ?>
				</div>

			<?php endif; ?>
		</div>


	</div>


</div>

<div>
	<div class="tabs">
		<ul>
			<?php foreach ($tabs_admin as $tab): ?>
				<li><a href="<?php echo $this->Html->url($tab['url']); ?>" title="<?php echo $tab['title']; ?>"><i class="<?php echo $tab['icon']; ?>"></i></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>


<div>
	<div class="tabs">
		<ul>
			<?php foreach ($tabs as $tab): ?>
				<li><a href="<?php echo $this->Html->url($tab['url']); ?>" title="<?php echo $tab['title']; ?>"><i class="<?php echo $tab['icon']; ?>"></i></a></li>
			<?php endforeach; ?>
		</ul>

		<div id="loading_indicator">
			<i class="icon-spinner icon-spin icon-2x"></i>
		</div>
	</div>
</div>
