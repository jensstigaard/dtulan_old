<div class="form">
	<h2><?php echo h($page['Page']['title']); ?></h2>

	<p><small><em>Updated: <?php echo $page['Page']['time_latest_update']; ?> by <?php echo $page['LatestUpdateBy']['name']; ?></em></small></p>

	<p><?php echo h($page['Page']['text']); ?></p>
</div>