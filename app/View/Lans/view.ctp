<?php
echo $this->Html->css(array(
	 'lan',
	 'lan.participants'
		  ), null, array(
	 'inline' => false
));
?>
<div>

	<?php if ($is_admin): ?>
		<?php
		$admin_links_new = array(
			 array(
				  'title' => 'Crew',
				  'icon' => 'icon-user-md',
				  'url' => array(
						'controller' => 'crew',
						'action' => 'add',
						$lan['Lan']['slug']
				  )
			 ),
			 array(
				  'title' => 'Tournament',
				  'icon' => 'icon-trophy',
				  'url' => array(
						'controller' => 'tournaments',
						'action' => 'add',
						$lan['Lan']['id']
				  )
			 ),
		);
		$admin_links_connect = array(
			 array(
				  'title' => 'Food-menu',
				  'icon' => 'icon-coffee',
				  'url' => array(
						'controller' => 'lan_food_menus',
						'action' => 'add',
						$lan['Lan']['id']
				  )
			 ),
			 array(
				  'title' => 'Pizza-menu',
				  'icon' => 'icon-food',
				  'url' => array(
						'controller' => 'lan_pizza_menus',
						'action' => 'add',
						$lan['Lan']['id']
				  )
			 ),
//				 array(
//					  'title' => '',
//					  'icon' => '',
//					  'url' => array(
//							'controller' => '',
//							'action' => '',
//					  )
//				 ),
		);
		?>
		<div class="btn-group" style="float:right;">
			<div class="btn-group">
				<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-plus-sign"></i> New
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<?php foreach ($admin_links_new as $link): ?>
						<li>
							<?php
							echo $this->Html->link('<i class="icon-large ' . $link['icon'] . '"></i> ' . $link['title'], $link['url'], array(
								 'escape' => false,
							));
							?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="btn-group">
				<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-link"></i> Connect
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<?php foreach ($admin_links_connect as $link): ?>
						<li>
							<?php
							echo $this->Html->link('<i class="icon-large ' . $link['icon'] . '"></i> ' . $link['title'], $link['url'], array(
								 'escape' => false,
							));
							?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>

	<h1><?php echo $title; ?></h1>

	<div id="lan_overview">
		<div>
			<div class="lan_overview_item" rel="tooltip" title="Date">
				<i class="icon-calendar"></i>
				<span><?php echo $this->Time->format('d/m/y H:i', $lan['Lan']['time_start']) . ' - ' . $this->Time->format('d/m/y H:i', $lan['Lan']['time_end']); ?></span>
			</div>
			<div class="lan_overview_item" rel="tooltip" title="Price">
				<i class="icon-money"></i>
				<span><?php echo $lan['Lan']['price']; ?> DKK</span>

			</div>
			<div class="lan_overview_item" rel="tooltip" title="Participants">
				<i class="icon-group"></i>
				<span><?php echo $data['count_signups']; ?> (<?php echo $lan['Lan']['max_participants']; ?>)</span>
			</div>
			<div class="lan_overview_item" rel="tooltip" title="Fill rate">
				<i class="icon-dashboard"></i>
				<span><?php echo $data['fill_rate']; ?> %</span>
			</div>
		</div>

		<div>
			<div class="lan_overview_item" rel="tooltip" title="Crew-members">
				<i class="icon-user-md"></i>
				<span><?php echo $data['count_crew']; ?></span>
			</div>

			<div class="lan_overview_item" rel="tooltip" title="Tournaments">
				<i class="icon-trophy"></i>
				<span><?php echo $data['count_tournaments']; ?></span>
			</div>

			<div class="lan_overview_item" rel="tooltip" title="Sign up open">
				<?php if ($lan['Lan']['sign_up_open']): ?>
					<i class="icon-plus-sign"></i>
					<span>Signup open</span>
				<?php else: ?>
					<i class="icon-ban-circle"></i>
					<span>Signup not open</span>
				<?php endif; ?>
			</div>

			<?php if ($is_admin): ?>

				<div class="lan_overview_item" rel="tooltip" title="Published">
					<?php if ($lan['Lan']['published']): ?>
						<i class="icon-ok-sign"></i>
						<span>Published</span>
					<?php else: ?>
						<i class="icon-minus-sign"></i>
						<span>Unpublished</span>
					<?php endif; ?>
				</div>

				<div class="lan_overview_item" rel="tooltip" title="Need physical code">
					<?php if ($lan['Lan']['need_physical_code']): ?>
						<i class="icon-tags"></i>
						<span>Need code to signup</span>
					<?php else: ?>
						<i class="icon-ok-circle"></i>
						<span>No code needed to signup</span>
					<?php endif; ?>
				</div>

			<?php endif; ?>
		</div>
	</div>

</div>

<?php // pr($lan); ?>

<?php if ($is_cancelable): ?>
	<div>
		<h2>Cancel your signup</h2>
		<?php
		echo $this->Form->postLink(
				  '<i class="icon-large icon-remove"></i> Cancel your signup', array(
			 'controller' => 'lan_signups',
			 'action' => 'delete',
			 $lan['Lan']['slug']
				  ), array(
			 'confirm' => 'Are You sure you will cancel this signup?',
			 'escape' => false,
			 'class' => 'btn btn-danger'
				  )
		);
		?>
	</div>
<?php endif; ?>

<?php if ($is_admin): ?>
	<div>
		<div class="tabs">
			<ul>
				<?php foreach ($tabs_admin as $tab): ?>
					<li>
						<a href="<?php echo $this->Html->url($tab['url']); ?>" title="<?php echo $tab['title']; ?>">
							<i class="<?php echo $tab['icon']; ?>"></i>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="loading_indicator">
				<i class="icon-spinner icon-spin icon-2x"></i>
			</div>
		</div>
	</div>
<?php endif; ?>


<div>
	<div class="tabs">
		<ul>
			<?php foreach ($tabs as $tab): ?>
				<li>
					<a href="<?php echo $this->Html->url($tab['url']); ?>" title="<?php echo $tab['title']; ?>">
						<i class="<?php echo $tab['icon']; ?>"></i>
					</a></li>
			<?php endforeach; ?>
		</ul>

		<div class="loading_indicator">
			<i class="icon-spinner icon-spin icon-2x"></i>
		</div>
	</div>
</div>
