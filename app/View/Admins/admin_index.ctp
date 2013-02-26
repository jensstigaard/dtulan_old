<?php echo $this->Html->css(array('admins/index'), null, array('inline' => false)); ?>
<div>
	<div style="float:right;">
		<?php
		echo $this->Html->link(
				  '<i class="icon-large icon-user-md"></i> New admin', array(
			 'controller' => 'admins',
			 'action' => 'add',
			 'admin' => true,
				  ), array(
			 'escape' => false,
			 'class' => 'btn btn-inverse',
				  )
		);
		?>
	</div>

	<h1>Admins (<?php echo count($admins); ?>)</h1>

	<?php if (!count($admins)): ?>
		<p>No admins.. can this be true? :D :D</p>
	<?php else: ?>
		<div class="floated-list" id="admin-list">
			<?php foreach ($admins as $user): ?>
				<?php
				$title = '';
				$title.='<small>';
				$title.= $user['User']['name'];
				$title.='<br />';
				$title.='+45 ' . $user['User']['phonenumber'];
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

	<?php endif; ?>

</div>