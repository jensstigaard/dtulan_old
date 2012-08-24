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
class TournamentTeam extends AppModel {
	public $belongsTo = array('Tournament');
	public $hasOne = array('Team');
}

?>
