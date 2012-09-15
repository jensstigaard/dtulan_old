<div class="unsigned_lan">
	<h1><?php echo $this->Html->image('24x24_PNG/001_36.png'); ?> Highlighted event</h1>
	<ul>
		<li><?php
$text = $this->Html->image('24x24_PNG/games.png', array('style' => 'margin-bottom:-16px;'));
$text.= ' <strong>' . $sidebar_current_lan['Lan']['title'];
$text.=	'</strong><br />';
$text.= '<small style="padding-left:28px;">';
if($sidebar_current_lan['Lan']['time_start'] > date('Y-m-d H:i:s')){
	$text.= 'Starts in ';
	$text.= $this->Time->timeAgoInWords($sidebar_current_lan['Lan']['time_start']);
}
else{
	$text.= '<strong style="color:green;">ON AIR!</strong>';
}
$text.= '</small>';

echo $this->Html->link($text, array('controller' => 'lans', 'action' => 'view', $sidebar_current_lan['Lan']['id']), array('escape' => false));
?></li>
	</ul>
</div>