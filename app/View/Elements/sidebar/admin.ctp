<?php if ($is_admin): ?>
<div>
	<h1>User lookup</h1>
	<div id="user_lookup">
		<?php echo $this->Form->input('string', array('label' => false)); ?>
		<div class="hidden" id="urlLookup"><?php echo $this->Html->link('#', array('controller' => 'users', 'action' => 'lookup')); ?></div>
		<div class="hidden" id="urlRedirect"><?php echo $this->Html->link('#', array('controller' => 'users', 'action' => 'profile')); ?></div>
	</div>
</div>
<div>
	<h1>Admin menu</h1>
	<ul>
		<li><?php echo $this->Html->link('LANS', array('controller' => 'lans', 'action' => 'index')); ?></li>
		<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index')); ?></li>
		<li><?php echo $this->Html->link('Pages', array('controller' => 'pages', 'action' => 'index')); ?></li>
		<li><?php echo $this->Html->link('Pizzas', array('controller' => 'pizza_categories', 'action' => 'index')); ?></li>
		<li><?php echo $this->Html->link('Payments', array('controller' => 'payments', 'action' => 'index')); ?></li>
		<li><?php echo $this->Html->link('New admin', array('controller' => 'admins', 'action' => 'add')); ?></li>
	</ul>
</div>
<?php endif; ?>