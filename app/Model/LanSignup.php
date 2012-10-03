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
			'validateUser' => array(
				'rule' => 'validateUser',
				'message' => 'Invalid user'
			),
			'checkNotInLan' => array(
				'rule' => 'validateUserInLan',
				'message' => 'Invalid signup'
			)
		),
		'lan_id' => array(
			'valid' => array(
				'rule' => 'validateLan',
				'message' => 'Invalid lan'
			)
		)
	);

	public function validateUserInLan($check) {
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

	public function validateUser($check) {
		if ($this->User->find('count', array(
					'conditions' => array(
						'User.id' => $check['user_id']
					)
						)
				)
				== 1) {
			return true;
		}

		return false;
	}

	public function validateLan($check) {
		if ($this->Lan->find('count', array('conditions' => array(
						'Lan.id' => $check['lan_id']
					)
						)
				)
				== 1) {
			return true;
		}

		return false;
	}

	public function countTotalInLan($lan_id) {
		return $this->find('count', array(
					'conditions' => array(
						'LanSignup.lan_id' => $lan_id
					)
						)
		);
	}

}

?>
