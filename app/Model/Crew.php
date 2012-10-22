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
		'User', 'Lan'
	);
	public $hasMany = array('Mark');

	public function isUserInCrew($lan_id, $user_id) {
		return $this->find('count', array('conditions' => array(
						'lan_id' => $lan_id,
						'user_id' => $user_id
					)
						)
				) == 1;
	}

	public function getCrewId($lan_id, $user_id) {
		$crew_member = $this->find('first', array(
			'conditions' => array(
				'lan_id' => $lan_id,
				'user_id' => $user_id
			)
				)
		);

		if (!isset($crew_member['Crew']['id'])) {
			throw new NotFoundException('Crew member not found');
		}
		return $crew_member['Crew']['id'];
	}

}

?>
