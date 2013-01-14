<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mark
 *
 * @author dengalepirat
 */
class Mark extends AppModel {

	public $useTable = 'crew_user_marks';
	public $belongsTo = array('Crew');
	public $hasOne = array('User');

}

?>
