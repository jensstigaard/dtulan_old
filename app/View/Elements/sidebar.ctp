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

	if (isset($pizza_waves) && count($pizza_waves)) {
		echo $this->element('sidebar/pizza/waves', array());
	}
	if (isset($is_orderable) && $is_orderable) {
		echo $this->element('sidebar/pizza/order', array());
	}
	if (isset($is_orderable_food) && $is_orderable_food) {
		echo $this->element('sidebar/order_food', array());
	}

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