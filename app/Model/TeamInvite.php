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
		
		$this->Team->id = $this->data['TeamInvite']['team_id'];
		
		return $this->Team->isLeader($check['user_invited_by_id']);
	}

}

?>
