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

	public function getIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug) {
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

	public function getTeamsList() {
		$teams = $this->Team->find('all', array(
			 'conditions' => array(
				  'Team.tournament_id' => $this->id
			 ),
			 'contain' => array(
				  'TeamUser' => array(
						'order' => array(
							 'is_leader' => 'desc'
						),
						'User' => array(
							 'id',
							 'name',
							 'email_gravatar',
							 'gamertag'
						)
				  ),
				  'TournamentWinner'
			 ),
			 'order' => array(
				  'TournamentWinner.place = 1' => 'desc',
				  'TournamentWinner.place = 2' => 'desc',
				  'TournamentWinner.place = 3' => 'desc',
			 )
				  ));


		if ($this->isLoggedIn()) {
			foreach ($teams as $index => $team) {
				$this->Team->id = $team['Team']['id'];
				$this->Team->TeamUser->User->id = $this->getLoggedInId();

				if ($this->Team->isUserPartOfTeam()) {
					$teams[$index]['Team']['is_part_of'] = true;
					break;
				}
			}
		}

		return $teams;
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
								  'User.email_gravatar',
								  'User.name'
							 )
						)
				  ));
	}

	public function getPlacesTaken() {
		$places_taken = $this->Team->find('all', array(
			 'conditions' => array(
				  'Team.tournament_id' => $this->id,
				  'TournamentWinner.place >' => 0
			 ),
			 'contain' => array(
				  'TournamentWinner' => array(
						'fields' => array(
							 'place'
						)
				  )
			 )
				  ));

		$places = array();
		foreach ($places_taken as $place_taken) {
			$places[] = $place_taken['TournamentWinner']['place'];
		}

		return $places;
	}

	public function getFrontTournaments() {

		return $this->find('all', array(
						'order' => array(
							 'Tournament.time_start' => 'desc'
						),
						'limit' => 3
				  ));
	}

	public function getPlacesNotTaken() {
		$places = array(1, 2, 3);

		$places_taken = $this->getPlacesTaken();

		return array_diff($places, $places_taken);
	}

	public function isPlaceTaken($place) {
		return $this->Team->find('count', array(
						'conditions' => array(
							 'Team.tournament_id' => $this->id,
							 'TournamentWinner.place' => $place
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
							 'conditions' => array(
								  'TeamUser.user_id' => $this->Team->TeamUser->User->id
							 ),
							 'fields' => array(
								  'id'
							 )
						)
				  )
			 )
				  ));

		return isset($tournament['Team'][0]['TeamUser'][0]['id']);
	}

	public function isAbleToCreateTeam() {
		if (!$this->isLoggedIn()) {
			return false;
		}

		$this->Team->TeamUser->User->id = $this->getLoggedInId();

		return $this->isSignupOpen() && !$this->isUserInTournament();
	}

	public function isSignupOpen() {
		$this->read(array('is_signup_open'));

		return $this->data['Tournament']['is_signup_open'];
	}

}

?>
