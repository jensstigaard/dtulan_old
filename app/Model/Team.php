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

	public $hasMany = array('TeamUser', 'TeamInvite');
	public $belongsTo = array('Tournament');
	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'invalid Team name'
			)
		)
	);

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
		if (count($user_ids) < $team['Tournament']['max_team_size']) {

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

}

?>
