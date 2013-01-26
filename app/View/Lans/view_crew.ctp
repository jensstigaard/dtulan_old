<?php echo $this->Html->script('general'); ?>
<div>

	<?php if (!count($crew)): ?>

		<p style="margin:10px;font-size: 14pt;"><i class="icon-exclamation-sign" style="font-size:16pt;"></i> No crew found</p>

	<?php else: ?>

		<div class="floated-list" id="participant-list">
			<?php foreach ($crew as $user): ?>
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
	<?php endif; ?> 
</div>