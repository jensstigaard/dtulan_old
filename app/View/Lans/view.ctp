<div>
	<h1><?php echo $title; ?></h1>

	<div class="tabs">
		<ul>
			<?php foreach($tabs as $tab): ?>
			<li><a href="<?php echo $this->Html->url($tab['url']); ?>" title="<?php echo $tab['title']; ?>"><?php echo $this->Html->image($tab['img']); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<div style="text-align:center;">
			<?php echo $this->Html->image('ajax-loader.gif', array('class' => 'hidden', 'id' => 'loading_indicator', 'alt' => 'loading ...')); ?>
		</div>
	</div>

</div>