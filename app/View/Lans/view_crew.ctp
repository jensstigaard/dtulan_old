<?php echo $this->Html->script('general'); ?>
<div>
	<?php if ($is_admin): ?>
		<div style="text-align: right;margin-bottom:10px;">
			<?php
			echo $this->Html->link(
					  'Add Crewmember', array(
				 'controller' => 'crew',
				 'action' => 'add',
				 $lan_slug
					  )
			);
			?>
		</div>
	<?php endif; ?>
	<?php if (!count($crew)): ?>
		<p>No crew yet :-)</p>
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
		<div style="clear:both;"></div>
	<?php endif; ?> 
</div>