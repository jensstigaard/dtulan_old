<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignup
 *
 * @author Jens
 */
class LanSignup extends AppModel {

	public $belongsTo = array(
		'User', 'Lan'
	);
	public $hasOne = array('LanInvite');
	public $hasMany = array('LanSignupDay');

	public $validate = array(
		'user_id' => array(
			'rule' => 'validateUser',
			'message' => 'Invalid signup'
		)
	);

	public function validateUser($check) {
		if ($this->find('count', array('conditions' => array(
						'LanSignup.user_id' => $check['user_id'],
						'LanSignup.lan_id' => $this->data['LanSignup']['lan_id'],
					)
						)
				)
				== 0) {
			return true;
		}

		return false;
	}

}

?>
