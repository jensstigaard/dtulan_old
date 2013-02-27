<?php echo $this->Html->css(array('front'), null, array('inline' => false)); ?>
<div class="row-fluid">
	<?php if (count($tournaments)): ?>
		<div id="upcoming-tournaments" class="box span6">
			<div id="tournaments-carousel" class="carousel slide" style="margin-bottom:0;">
				<ol class="carousel-indicators">
					<?php foreach ($tournaments as $x => $tournament): ?>
						<li data-target="#tournaments-carousel" data-slide-to="<?php echo $x; ?>"<?php echo $x == 0 ? ' class="active"' : ''; ?>></li>
					<?php endforeach; ?>
				</ol>
				<!-- Carousel items -->
				<div class="carousel-inner">
					<?php foreach ($tournaments as $x => $tournament): ?>
						<div class="item<?php echo $x == 0 ? ' active' : ''; ?>">
							<?php
							echo $this->Html->image('uploads/' . $tournament['Game']['Image']['thumbPath'], array(
								 'url' => array(
									  'controller' => 'tournaments',
									  'action' => 'view',
									  'lan_slug' => $tournament['Lan']['slug'],
									  'tournament_slug' => $tournament['Tournament']['slug']
								 )
							));
							?>
							<div class="carousel-caption">
								<strong style="display:block;margin-bottom:2px;">
									Upcoming tournament!
									<?php if ($tournament['Tournament']['is_signup_open']): ?>
										<em style="color: lawngreen">[Signup open]</em>
									<?php endif; ?>
								</strong>
								<?php echo $tournament['Tournament']['title']; ?>
								<em style="color: grey"><?php echo $tournament['Tournament']['time_start']; ?></em>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- Carousel nav -->
				<!--			<a class="carousel-control left" href="#tournaments-carousel" data-slide="prev">&lsaquo;</a>
							<a class="carousel-control right" href="#tournaments-carousel" data-slide="next">&rsaquo;</a>-->
			</div>
		</div>
	<?php endif; ?>

	<div class="box <?php if (count($tournaments)): ?>span6<?php endif; ?>" id="news-feed">
		<a class="twitter-timeline" href="https://twitter.com/dtu_lan" data-widget-id="306801364892254208" width="325">Tweets af @dtu_lan</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	</div>

</div>


<div class="box">
	<?php
	if ($is_admin) {
		echo $this->Html->link('<i class="icon-large icon-pencil"></i> Edit page', array(
			 'controller' => 'pages',
			 'action' => 'edit',
			 $page['Page']['id'],
//				 'admin' => true
				  ), array(
			 'escape' => false,
			 'class' => 'btn btn-small btn-inverse',
			 'style' => 'float:right;'
		));
	}
	?>
	<h1><?php echo $page['Page']['title']; ?></h1>
	<p>
		<small>
			<em>
				Updated: <?php echo $page['Page']['time_latest_update']; ?> by
				<?php echo $this->Html->link($page['LatestUpdateBy']['name'], array('controller' => 'users', 'action' => 'view', $page['LatestUpdateBy']['id'])); ?>
			</em>
		</small>
	</p>
	<div>
		<?php echo $page['Page']['text']; ?>
	</div>
</div>