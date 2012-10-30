<?php

if (!$is_loggedin) {
	echo $this->element('sidebar/user_notloggedin', array());
	if (count($lans_highlighted)) {
		echo $this->element('sidebar/lans_highlighted', array(
			'cache' => array(
				'lans_highlighted' => $lans_highlighted
			)
				)
		);
	}
} else {
	echo $this->element('sidebar/user', array());
	echo $this->element('sidebar/pizza', array());
	echo $this->element('sidebar/food', array());
	if (count($lans_highlighted)) {
		echo $this->element('sidebar/lans_highlighted', array(
			'cache' => array(
				'lans_highlighted' => $lans_highlighted
			)
				)
		);
	}
	echo $this->element('sidebar/invites', array());
	echo $this->element('sidebar/admin', array());
}