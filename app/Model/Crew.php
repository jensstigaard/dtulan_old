<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Crew
 *
 * @author Jens
 */
class Crew extends AppModel {
    
	public $belongsTo = array(
		'User', 'Lan'
	);
        public $hasMany = array('Mark');

}

?>
