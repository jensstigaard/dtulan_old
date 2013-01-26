<?php
echo $this->Html->script(array(
	 'ajax/all_links',
	 'general'
));
?>

<div class="ajax_area">
	<?php if (!count($participants)): ?>
		<p>No participants yet</p>
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

//					if ($is_admin) {
//						$title.='<small class="phonenumber">';
//						$title.='+45 ' . $user['User']['phonenumber'];
//						$title.='</small>';
//					}

				echo $this->Html->link(
						  $title, array(
					 'controller' => 'users',
					 'action' => 'profile',
					 $user['User']['id']), array(
					 'style' => 'background-image: url(http://www.gravatar.com/avatar/' . md5(strtolower($user['User']['email_gravatar'])) . '?s=120&amp;r=r);',
					 'escape' => false,
					 'class' => 'item'
						  )
				);
				?>
			<?php endforeach; ?>
		</div>

		<div style="clear:both;"></div>

		<div class="participants-list-sort-links">
			<?php echo $this->Paginator->numbers(array('class' => 'load_inline')); ?>
		</div>

		<div style="clear:both;"></div>
	<?php endif; ?>
</div>