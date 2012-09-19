<?php

if (!$is_loggedin) {
	echo $this->element('sidebar/user_notloggedin', array());
	if (isset($sidebar_current_lan)) {
		echo $this->element('sidebar/current_lan');
	}
} else {
	echo $this->element('sidebar/user', array());
	echo $this->element('sidebar/pizza', array());
	if (isset($sidebar_current_lan)) {
		echo $this->element('sidebar/current_lan', array(
			'cache' => array(
				'sidebar_current_lan' => $sidebar_current_lan
			)
				)
		);
	}
	echo $this->element('sidebar/invites', array());
	echo $this->element('sidebar/admin', array());
}