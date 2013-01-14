<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanInvite
 *
 * @author Jens
 */
class LanInvite extends AppModel {

	public $belongsTo = array(
		'Lan',
		'Guest' => array(
			'className' => 'User',
			'foreignKey' => 'user_guest_id'
		),
		'Student' => array(
			'className' => 'User',
			'foreignKey' => 'user_student_id'
		),
		'LanSignup'
	);
	public $validate = array(
		'user_guest_id' => array(
			'rule' => 'validateUser',
			'message' => 'Invalid user'
		)
	);

	public function validateUser($check) {
		if ($this->find('count', array('conditions' => array(
						'LanInvite.lan_id' => $this->data['LanInvite']['lan_id'],
						'LanInvite.user_guest_id' => $check['user_guest_id'],
					)
						)
				)
				== 0) {
			return true;
		}

		return false;
	}

	public function isNotAccepted($lan_id, $user_id) {
		return $this->find('count', array('conditions' => array(
						'LanInvite.lan_id' => $lan_id,
						'LanInvite.user_guest_id' => $user_id,
						'LanInvite.accepted' => 0
					)
						)
				)
				!= 1;
	}

}

?>
