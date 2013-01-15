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
		'User',
		'Lan'
	);
	public $hasOne = array(
		'LanInvite' => array(
			'dependent' => true
		),
		'LanSignupCode'
	);
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
		),
		'code' => array(
			'validCode' => array(
				'rule' => 'validateCode',
				'message' => 'Invalide code'
			)
		),
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
		$this->Lan->id = $check['lan_id'];

		if ($this->Lan->isSignupPossible()) {
			return true;
		}

		return false;
	}

	public function validateCode($check) {
		$this->Lan->id = $this->Lan->data['Lan']['id'];

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found.');
		}

		$this->Lan->read(array('need_physical_code'));

		if (!$this->Lan->data['Lan']['need_physical_code']) {
			return true;
		} elseif ($this->Lan->LanSignupCode->isNotUsed($check['code'])) {
			return true;
		}

		return false;
	}

	public function getLanSignupsCrew() {

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $this->Lan->id);
		}

		$crew = $this->Lan->Crew->find('all', array(
			'conditions' => array(
				'Crew.lan_id' => $this->Lan->id
			),
			'recursive' => 0,
			'fields' => array(
				'Crew.user_id'
			)
				)
		);

		$crew_user_ids = array();
		foreach ($crew as $crew_member) {
			$crew_user_ids[] = $crew_member['Crew']['user_id'];
		}

		return $this->find('all', array(
					'conditions' => array(
						'LanSignup.lan_id' => $this->Lan->id,
						'LanSignup.user_id' => $crew_user_ids
					),
					'recursive' => 2
						)
		);
	}

	public function getLanSignupsCrewIds() {
		$crew_user_data = $this->getLanSignupsCrew();

		$lan_crew_user_ids = array();
		foreach ($crew_user_data as $crew) {
			$lan_crew_user_ids[] = $crew['User']['id'];
		}

		// Crew signed up for LAN
		$lan_signups_crew = $this->Lan->LanSignup->find('all', array(
			'conditions' => array(
				'LanSignup.lan_id' => $this->Lan->id,
				'LanSignup.user_id' => $lan_crew_user_ids,
			),
			'recursive' => -1,
				)
		);

		$lan_signups_id_crew = array();
		foreach ($lan_signups_crew as $lan_signup_crew) {
			$lan_signups_id_crew[] = $lan_signup_crew['LanSignup']['id'];
		}

		return $lan_signups_id_crew;
	}

	public function getDataForDeletion() {

		$result = $this->find('first', array(
			'conditions' => array(
				'LanSignup.lan_id' => $this->Lan->id,
				'LanSignup.user_id' => $this->User->id
			),
			'fields' => array(
				'LanSignup.id',
				'Lan.price'
			)
				)
		);

		$this->id = $result['LanSignup']['id'];
		
		if (!$this->exists()) {
			throw new NotFoundException('Lan Signup not found');
		}

		return $result;
	}

}

?>
