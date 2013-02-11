<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tournament
 *
 * @author Superkatten
 */
class Team extends AppModel {

	public $hasOne = array(
		 'TournamentWinner'
	);
	public $belongsTo = array(
		 'Tournament'
	);
	public $hasMany = array(
		 'TeamUser' => array(
			  'dependent' => true
		 ),
		 'TeamInvite' => array(
			  'dependent' => true
		 ),
	);
	public $validate = array(
		 'name' => array(
			  'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'invalid Team name'
			  )
		 )
	);

	public function countMembers() {
		return $this->TeamUser->find('count', array(
						'conditions' => array(
							 'TeamUser.team_id' => $this->id
						)
				  ));
	}

	// Refactor!!!
	public function getInviteableUsers($team_id = null) {
		$this->id = $team_id;

		if (!$this->exists()) {
			throw new NotFoundException('Team not found');
		}

		$this->recursive = 2;
		$team = $this->read();

		$user_ids = array();
		foreach ($team['TeamInvite'] as $user) {
			$user_ids[] = $user['User']['id'];
		}

		foreach ($team['TeamUser'] as $user) {
			$user_ids[] = $user['user_id'];
		}

		$users_list = array();

		// Only the max team size is it possible to invite
		if (count($user_ids) < $team['Tournament']['team_size']) {

			$users = $this->Tournament->Lan->LanSignup->find('all', array('conditions' => array(
					  'NOT' => array(
							'LanSignup.user_id' => $user_ids,
					  ),
					  'LanSignup.lan_id' => $team['Tournament']['Lan']['id']
				 )
					  )
			);

			foreach ($users as $user) {
				$users_list[$user['User']['id']] = $user['User']['name'];
			}
		}

		return $users_list;
	}

	public function isLeader($team_id, $user_id) {
		$this->id = $team_id;

		if (!$this->exists()) {
			throw new NotFoundException('Team not found');
		}

		$this->TeamUser->User->id = $user_id;

		if (!$this->TeamUser->User->exists()) {
			throw new NotFoundException('User not found');
		}

		return $this->TeamUser->find('count', array(
						'conditions' => array(
							 'team_id' => $team_id,
							 'user_id' => $user_id,
							 'is_leader' => true
						)
							 )
				  ) == 1;
	}

	public function isWinner() {

		$team = $this->find('first', array(
			 'conditions' => array(
				  'Team.id' => $this->id
			 ),
			 'fields' => array(
				  'TournamentWinner.place'
			 )
				  ));

		return $team['TournamentWinner']['place'] > 0;
	}

}

?>
