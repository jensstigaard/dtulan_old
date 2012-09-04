<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignup
 *
 * @author Jens
 */
class LanSignup extends AppModel {

	public $belongsTo = array(
		'User', 'Lan'
	);

	public $hasMany = array('LanSignupDay');

	public $hasOne = array('LanInvite');

}

?>
