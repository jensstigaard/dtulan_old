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

	public function userNotInTournament($user_id, $tournament_id) {
		
	}

}

?>
