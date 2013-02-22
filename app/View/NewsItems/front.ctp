<div class="row-fluid">
	<div <?php if (count($tournaments)): ?>class="span6"<?php endif; ?> id="news-feed">
		<h1>News</h1>
		<div>
			<?php if (!count($latest_news)): ?>
				<div class="alert alert-error">
					<i class="icon-large icon-exclamation-sign pull-left"></i>
					No news found
				</div>
			<?php else: ?>
				<?php foreach ($latest_news as $news_item): ?>
					<div class="item">
						<h3>
							<?php echo $news_item['NewsItem']['title']; ?>
						</h3>
						<em><small><?php echo $news_item['NewsItem']['time_created']; ?></small></em>
						<p><?php echo nl2br($news_item['NewsItem']['text']); ?></p>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php if (count($tournaments)): ?>
		<div class="span6">	
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
</div>

<div>
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

<?php
// pr($tournaments); ?>