<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignupDay
 *
 * @author Jens
 */
class LanSignupDay extends AppModel {

	public $belongsTo = array('LanSignup', 'LanDay');
	public $validate = array(
		'lan_day_id' => array(
			'required' => array(
				'rule' => 'availableLanDay',
				'message' => 'No seats left at this date'
			)
		)
	);
	public $order = array(
		'LanDay.date' => 'asc',
	);

	public function availableLanDay($check) {
		if ($this->LanDay->seatsLeft($check['lan_day_id']) > 0) {
			return true;
		} else {
			return false;
		}
//		return false;
	}

}

?>
