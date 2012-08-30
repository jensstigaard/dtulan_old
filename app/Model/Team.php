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

	public $hasMany = array('TeamUser');
	public $hasAndBelongsToMany = array('Invite' => array(
			'className' => 'User',
			'joinTable' => 'team_invites'
		)
	);
	public $belongsTo = array('Tournament');
	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'invalid Team name'
			)
		)
	);

}

?>
