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

	public $belongsTo = array(
		 'Lan',
		 'Game'
	);
	public $hasMany = array(
		 'Team',
	);
	public $validate = array(
		 'title' => array(
			  'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => 'The title cannot be empty'
			  )
		 ),
		 'slug' => array(
			  'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Slug cannot be empty'
			  ),
//			  'validate slug' => array(
//					'rule' => 'validateSlug',
//					'message' => 'Invalid slug!'
//			  )
		 ),
		 'team_size' => array(
			  'is between' => array(
					'rule' => array('between', 1, 16),
					'message' => 'Invalid team size'
			  )
		 )
	);

	public function beforeValidate($options = array()) {
		parent::beforeValidate($options);

		if (isset($this->data['Tournament']['title'])) {
			$this->data['Tournament']['slug'] = $this->stringToSlug($this->data['Tournament']['title']);
		}
	}

	public function getLanIdByTournamentId($id) {

		$this->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException('No tournament with id: ' . $id);
		}

		$this->read('lan_id');

		return $this->data['Tournament']['lan_id'];
	}

	public function getTournamentIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug) {
		$this->Lan->id = $this->Lan->getIdBySlug($lan_slug);

		$result = $this->find('first', array(
			 'conditions' => array(
				  'Tournament.lan_id' => $this->Lan->id,
				  'Tournament.slug' => $tournament_slug
			 ),
			 'fields' => array(
				  'id',
//				  'published'
			 )
				  ));

		if (!isset($result['Tournament']['id'])) {
			throw new NotFoundException('Tournament not found with slug: ' . $tournament_slug);
		}

		$this->id = $result['Tournament']['id'];

		if (!$this->exists()) {
			throw new NotFoundException('Tournament not found with slug: ' . $tournament_slug);
		}

//		if (!$result['Tournament']['published']) {
//			if (!$this->isLoggedIn()) {
//				throw new UnauthorizedException('You are not authorized to see this page');
//			}
//
//			$this->Lan->LanSignup->User->id = $this->getLoggedInId();
//			if (!($this->isYouAdmin() || $this->Lan->isUserAttendingAsCrew())) {
//				throw new UnauthorizedException('You are not authorized to see this page');
//			}
//		}

		return $this->id;
	}

	public function getTeamIds($id) {
		$this->id = $id;
		if (!$this->exists()) {
			throw new NotFoundException('Tournament not found');
		}

		$team_ids = $this->Team->find('all', array(
			 'conditions' => array(
				  'Team.tournament_id' => $id
			 ),
			 'fields' => array(
				  'Team.id'
			 ),
			 'recursive' => -1
				  )
		);

		$team_ids_formatted = array();
		foreach ($team_ids as $team) {
			$team_ids_formatted[] = $team['Team']['id'];
		}

		return $team_ids_formatted;
	}

	public function getWinnerTeams() {

		return $this->Team->find('all', array(
						'conditions' => array(
							 'Team.tournament_id' => $this->id,
							 'TournamentWinner.place >' => 0
						),
						'fields' => array(
							 'Team.name',
							 'TournamentWinner.place'
						),
						'contain' => array(
							 'TournamentWinner' => array(
								  'order' => array(
										'place ASC',
								  ),
							 ),
							 'TeamUser' => array(
								  'User.id',
								  'User.name'
							 )
						)
				  ));
	}

	public function isUserInTournament() {

		$tournament = $this->find('first', array(
			 'conditions' => array(
				  'Tournament.id' => $this->id
			 ),
			 'contain' => array(
				  'Team' => array(
						'TeamUser' => array(
							 'User' => array(
								  'conditions' => array(
										'User.id' => $this->Team->TeamUser->User->id
								  ),
								  'fields' => array(
										'id'
								  )
							 )
						)
				  )
			 )
				  ));

		return isset($tournament['Team']['TeamUser']['User']['id']);
	}

	public function isAbleToCreateTeam() {
		if (!$this->isLoggedIn()) {
			return false;
		}

		$this->Team->TeamUser->User->id = $this->getLoggedInId();

		return !$this->isUserInTournament();
	}

}

?>
