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

		if ($this->TournamentWinner->isYouAdmin($user)) {
			return true;
		}
		return false;
	}

	public function add($team_id, $place) {

		if ($place < 1 && $place > 3) {
			throw new MethodNotAllowedException('Invalid place');
		}

		$this->TournamentWinner->Team->id = $team_id;

		if (!$this->TournamentWinner->Team->exists()) {
			throw new NotFoundException('Team not found with id: ' . $team_id);
		}

		if ($this->TournamentWinner->Team->isWinner()) {
			throw new MethodNotAllowedException('Team is already a winner of tournament');
		}

		$team = $this->TournamentWinner->Team->find('first', array(
			 'conditions' => array(
				  'Team.id' => $this->TournamentWinner->Team->id
			 ),
			 'contain' => array(
				  'Tournament' => array(
						'fields' => array(
							 'id',
							 'slug'
						),
						'Lan' => array(
							 'fields' => array(
								  'slug'
							 )
						)
				  )
			 )
				  ));

		$this->TournamentWinner->Team->Tournament->id = $team['Tournament']['id'];

		if ($this->TournamentWinner->Team->Tournament->isPlaceTaken($place)) {
			throw new MethodNotAllowedException('Place already taken');
		}

		$this->TournamentWinner->create();

		$this->TournamentWinner->set(array(
			 'team_id' => $team_id,
			 'place' => $place,
			 'user_id' => $this->TournamentWinner->getLoggedInId()
		));

		if ($this->TournamentWinner->save()) {
			$this->Session->setFlash('TournamentWinner has been saved', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Unable to save tournament winner', 'default', array(), 'bad');
		}

		$this->redirect(array(
			 'controller' => 'tournaments',
			 'action' => 'view',
			 'lan_slug' => $team['Tournament']['Lan']['slug'],
			 'tournament_slug' => $team['Tournament']['slug']
		));
	}

}

?>
