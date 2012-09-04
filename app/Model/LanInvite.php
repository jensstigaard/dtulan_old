<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanInvite
 *
 * @author Jens
 */
class LanInvite extends AppModel {

	public $belongsTo = array(
		'Lan',
		'Guest' => array(
			'className' => 'User',
			'foreignKey' => 'user_guest_id'
		),
		'Student' => array(
			'className' => 'User',
			'foreignKey' => 'user_student_id'
		),
		'LanSignup'
	);

}

?>
