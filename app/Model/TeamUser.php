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
	public $belongsTo = array('Team','User');
}

?>
