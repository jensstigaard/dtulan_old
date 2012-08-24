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
	public $hasAndBelongsToMany = array('Team' => array('joinTable' => 'tournament_teams'));
}

?>
