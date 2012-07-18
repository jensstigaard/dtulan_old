<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignups
 *
 * @author Jens
 */
class LanSignup extends AppModel {

	public $belongsTo = array(
		'User', 'Lan'
	);

}

?>
