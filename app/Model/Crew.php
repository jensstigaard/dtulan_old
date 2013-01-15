<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Crew
 *
 * @author Jens
 */
class Crew extends AppModel {

	public $belongsTo = array(
		'Lan'
	);
	public $hasMany = array('Mark');
	public $validate = array(
		'lan_id' => array(
			'lan exists' => array(
				'rule' => 'validateLanExists',
				'message' => 'LAN does not exist'
			),
			'validate lan' => array(
				'rule' => 'validateLan',
				'message' => 'Lan is not valid'
			)
		),
		'user_id' => array(
			'validate user' => array(
			),
			'validate user as crew in lan' => array(
				'rule' => 'validateUserInLan',
				'message' => 'User already crew in this LAN'
			)
		)
	);

	public function validateLanExist($check) {
		$this->Lan->id = $check['lan_id'];
		return $this->Lan->exists();
	}

	public function validateLanNotPast($check) {
		$this->Lan->id = $check['lan_id'];

		return !$this->Lan->isPast();
	}

	public function validateUserInLan($check) {
		$this->Lan->User->id = $check['user_id'];
		$this->Lan->id = $this->data['Crew']['lan_id'];

		if (!$this->isUserInCrewForLan()) {
			return true;
		}

		return false;
	}

	public function isUserInCrewForLan() {
		return $this->find('count', array('conditions' => array(
						'lan_id' => $this->Lan->id,
						'user_id' => $this->Lan->User->id
					)
						)
				) == 1;
	}

	public function getCrewId($lan_id, $user_id) {
		$crew_member = $this->find('first', array(
			'conditions' => array(
				'lan_id' => $lan_id,
				'user_id' => $user_id
			),
			'fields' => array(
				'id'
			)
				)
		);

		$this->id = $crew_member['Crew']['id'];
		
		if(!$this->exists()){
			throw new NotFoundException('Crew not found');
		}
		return $this->id;
	}

}

?>
