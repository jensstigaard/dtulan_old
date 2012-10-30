<?php foreach ($lans_highlighted as $lan): ?>
	<div style="padding-bottom: 0;">
		<ul>
			<li><?php
	$text = $this->Html->image('24x24_PNG/games.png', array('style' => 'margin:0 0 -16px -5px;padding-right:2px;'));
	$text.= ' <strong>' . $lan['Lan']['title'];
	$text.= '</strong><br />';
	$text.= '<small style="padding-left:26px;">';
	if ($lan['Lan']['time_start'] > date('Y-m-d H:i:s')) {
		$text.='<span style="color:yellow">';
		$text.= 'Starts ';
		$text.= (strpos($this->Time->timeAgoInWords($lan['Lan']['time_start']), 'on')>-1) ? '' : 'in ';
		$text.='<strong>';
		$text.= $this->Time->timeAgoInWords($lan['Lan']['time_start']);
		$text.='</strong></span>';
	} elseif($lan['Lan']['time_end'] < date('Y-m-d H:i:s')) {
		$text.= '<strong style="color:grey;">PREVIOUS EVENT</strong>';
	}
	else{
		$text.= '<strong style="color:green;">EVENT ON AIR!</strong>';
	}
	$text.= '</small>';

	echo $this->Html->link($text, array(
		'controller' => 'lans',
		'action' => 'view',
		$lan['Lan']['slug']
			), array('escape' => false));
	?></li>
		</ul>
	</div>
<?php endforeach; ?>