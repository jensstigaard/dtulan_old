<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TournamentWinnersController
 *
 * @author Jens
 */
class TournamentWinnersController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Tournament->isYouAdmin($user)) {
			return true;
		}
		return false;
	}

	public function add($team_id) {

		$this->TournamentWinner->Team->id = $team_id;

		if (!$this->TournamentWinner->Team->exists()) {
			throw new NotFoundException('Team not found with id: ' . $team_id);
		}
		
		if($this->TournamentWinner->Team->isWinner()){
			throw new MethodNotAllowedException('Team is already a winner of tournament');
		}
		
		if($this->request->is('post')){
			if($this->TournamentWinner->save($this->request->data)){
				
			}
		}
		
	}

}

?>
