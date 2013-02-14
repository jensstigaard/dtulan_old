<div class="row-fluid">
	<div class="span5">
		<h1>News</h1>
		<hr />
		<div>
			<?php if (!count($latest_news)): ?>
				<p>No news</p>
			<?php else: ?>
				<?php foreach ($latest_news as $news_item): ?>
					<div>
						<small><?php echo $news_item['NewsItem']['date']; ?></small>
						<h2><?php echo $news_item['NewsItem']['title']; ?></h2>
						<p><?php echo $news_item['NewsItem']['text']; ?></p>
						<hr />
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="span7">
		<div id="myCarousel" class="carousel slide" style="background: #000;margin-bottom:0;">
			<ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
			</ol>
			<!-- Carousel items -->
			<div class="carousel-inner">
				<div class="active item">
					<?php echo $this->Html->image('logos/games/heroes_of_newerth.png', array(
						 'url' => array(
							  'controller' => 'tournaments',
							  'action' => 'view',
							  'lan_slug' => 'e2012',
							  'tournament_slug' => 'test'
						 )
					)); ?>
					<div class="carousel-caption">
						Text!!!
					</div>
				</div>
				<div class="item">
					<?php echo $this->Html->image('logos/games/league_of_legends.png'); ?>
					<div class="carousel-caption">
						League of Legends
					</div>
				</div>
				<div class="item">
					<?php echo $this->Html->image('logos/games/starcraft_2.png'); ?>
					<div class="carousel-caption">
						StarCraft 2
					</div>
				</div>
			</div>
			<!-- Carousel nav -->
			<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
		</div>
	</div>
</div>

<div class="row">
	<div>
		<h1><?php echo $page['Page']['title']; ?></h1>
		<p>Latest update by <?php echo $page['LatestUpdateBy']['name']; ?>, <?php echo $page['Page']['time_latest_update']; ?></p>
		<div>
			<?php echo $page['Page']['text']; ?>
		</div>
	</div>
</div>