<?php

if (!$logged_in) {
	echo $this->element('sidebar/user_notloggedin', array());
} else {
	echo $this->element('sidebar/pizza', array());
	echo $this->element('sidebar/user', array());
	echo $this->element('sidebar/invites', array());
	echo $this->element('sidebar/admin', array());
}