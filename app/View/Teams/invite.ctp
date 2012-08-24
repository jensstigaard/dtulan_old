<div class="form">
	<?php echo $this->Form->create(); ?>
    <fieldset>
        <legend><?php echo $team['Team']['name']?> member invitation</legend>
		<?php echo $this->Form->input('user_id'); ?>
		</fieldset>
	<?php echo $this->Form->end(__('Invite')); ?>
	
	<h3>Members:</h3>
		<table>
		<tr>
		<th>Username:</th>
		<th>Leader:</th>
		</tr>
		<?php foreach($team['User'] as $user): ?>
		<tr>
			<td><?php echo $user['gamertag']; ?><td>
		</tr>
		<?php endforeach;?>
	</table>
	<?php pr($team) ?>
</div>