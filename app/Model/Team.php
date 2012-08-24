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
	public $hasAndBelongsToMany = array('Tournament' => array('joinTable' => 'tournament_teams'));
	public $hasMany = array('TeamUsers');
}

?>
