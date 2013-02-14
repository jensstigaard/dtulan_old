<?php
echo $this->Html->script(array(
	 'ajax/all_links',
	 'general'
));
?>

<div class="ajax_area">

	<?php if (!count($participants)): ?>

		<p style="margin:10px;font-size: 14pt;"><i class="icon-exclamation-sign" style="font-size:16pt;"></i> No participants yet</p>

	<?php else: ?>

		<div class="participants-list-sort-links">
			Sort by: <?php echo $this->Paginator->sort('User.name', 'Name', array('class' => 'load_inline')); ?>
		</div>

		<div class="floated-list" id="participant-list">
			<?php foreach ($participants as $user): ?>
				<?php
				$title = '';
				$title.='<small>';
				$title.= $user['User']['name'];
				$title.='</small>';

				echo $this->Html->link(
						  $title, array(
					 'controller' => 'users',
					 'action' => 'view',
					 $user['User']['id']
						  ), array(
					 'style' => 'background-image: url(http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=120&amp;r=r);',
					 'escape' => false,
					 'class' => 'item'
						  )
				);
				?>
			<?php endforeach; ?>
		</div>

		<div class="pagination" style="text-align: right;">
			<ul style="width:480px;">
				<?php
				echo $this->Paginator->numbers(array(
					 'tag' => 'li',
					 'currentTag' => 'a',
					 'class' => 'load_inline',
					 'separator' => false
				));
				?>
			</ul>
		</div>

		<div style="clear:both;"></div>
	<?php endif; ?>
</div>