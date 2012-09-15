<?php

if (!$is_loggedin) {
	echo $this->element('sidebar/user_notloggedin', array());
} else {
	echo $this->element('sidebar/pizza', array());
	echo $this->element('sidebar/user', array());
	if(isset($sidebar_current_lan)){
		echo $this->element('sidebar/current_lan', array());
	}
	echo $this->element('sidebar/invites', array());
	echo $this->element('sidebar/admin', array());
}