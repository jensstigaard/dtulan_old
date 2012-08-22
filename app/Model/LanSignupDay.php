<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignupDay
 *
 * @author Jens
 */
class LanSignupDay extends AppModel {
	public $belongsTo = array('LanSignup', 'LanDay');

}

?>
