<div>
	<h1>News</h1>
	<table>
	<?php foreach($news as $news_item): ?>
		<tr>
			<td><?php echo $news_item['NewsItem']['title']; ?></td>
			<td><?php echo $this->Html->link('<i class="icon-pencil"></i>', array(
				 'action' => 'edit',
				 $news_item['NewsItem']['id'],
				 'crew' => true
			), array(
				 'escape' => false,
				 'class' => 'btn btn-mini btn-inverse'
			)); ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>