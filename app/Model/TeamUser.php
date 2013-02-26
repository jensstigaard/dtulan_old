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
class TeamUser extends AppModel {

	public $belongsTo = array(
		'Team',
		'User'
	);
	public $validate = array(
		'user_id' => array(
			'validUser' => array(
				'rule' => 'userNotInTournament',
				'message' => 'User already a part of the tournament'
			)
		)
	);

	public function userNotInTournament($check) {
		
		$db = $this->getDataSource();
		
		$total = $db->fetchAll("
			SELECT COUNT(TeamUser.id) AS TeamUsers
				FROM `team_users` AS TeamUser
					INNER JOIN `teams` AS Team
						ON TeamUser.team_id = Team.id
				WHERE Team.tournament_id = ? AND TeamUser.user_id = ?", array($this->data['Team']['tournament_id'], $check['user_id']));
		
		return $total[0][0]['TeamUsers'] == 0;
		
	}

}

?>
