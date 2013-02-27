<div class="box">
	<?php
	echo $this->Form->create();
	?>
	<div style="float:right;">
		<?php
		echo $this->Html->image('uploads/thumb_210w_' . $imgPath);
		?>
	</div>
	<?php
	echo $this->Form->inputs(array('title'));
	echo $this->Form->end(__('Submit'));
	?>
</div>