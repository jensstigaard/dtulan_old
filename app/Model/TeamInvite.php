<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TeamInvite extends AppModel {

	public $belongsTo = array('User', 'Team');
	public $validate = array(
		'user_invited_by_id' => array(
			'isUserLeader' => array(
				'rule' => 'validateIsUserLeader',
				'message' => 'You are not leader of team'
			)
		)
	);

	public function validateIsUserLeader($check) {
		return $this->Team->isLeader($this->data['TeamInvite']['team_id'], $check['user_invited_by_id']);
	}

	public function getTeamInvites($user_id) {
		return $this->find('all', array(
					'conditions' => array(
						'user_id' => $user_id
					),
					'recursive' => 0
						)
		);
	}

}

?>
