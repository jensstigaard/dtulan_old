<?php foreach ($lans_highlighted as $lan): ?>
	<div class="lan_highlighted">
		<ul>
			<li><?php
	$text = '<i class="icon-bullhorn icon-2x"></i>';
	$text.= '<strong>' . $lan['Lan']['title'] . '</strong>';
	$text.= '<small>';
	if ($lan['Lan']['time_start'] > date('Y-m-d H:i:s')) {
		$text.='<span class="upcoming">';
		$text.= 'Starts ';
		$text.= (strpos($this->Time->timeAgoInWords($lan['Lan']['time_start']), 'on') > -1) ? '' : 'in ';
		$text.='<strong>';
		$text.= $this->Time->timeAgoInWords($lan['Lan']['time_start']);
		$text.='</strong>';
		$text.='</span>';
	} elseif ($lan['Lan']['time_end'] < date('Y-m-d H:i:s')) {
		$text.= '<span class="previous">PREVIOUS EVENT</span>';
	} else {
		$text.= '<span class="onair">EVENT ON AIR!</span>';
	}
	$text.= '</small>';

	echo $this->Html->link($text, array(
		 'controller' => 'lans',
		 'action' => 'view',
		 'slug' => $lan['Lan']['slug']
			  ), array('escape' => false));
	?></li>
		</ul>
	</div>
<?php endforeach; ?>