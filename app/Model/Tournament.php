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
class Tournament extends AppModel {
	public $belongsTo = array('Lan', 'Game');
	public $hasMany = array('Team');

	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'The title cannot be empty'
			)
		),

		'team_size' => array(
			'is between' => array(
				'rule' => array('between', 1, 16),
				'message' => 'Invalid team size'
			)
		)
	);

	public function ag(){
		
	}
}

?>
