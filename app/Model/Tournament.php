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
		 'Game',
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
			 'fields' => array(
				  'id',
				  'name',
				  'slug'
			 ),
			 'contain' => array(
				  'TournamentWinner' => array(
						'fields' => array(
							 'place'
						)
				  ),
				  'TeamUser' => array(
						'order' => array(
							 'is_leader' => 'desc'
						),
						'User' => array(
							 'fields' => array(
								  'id',
								  'name',
								  'email_gravatar',
								  'gamertag'
							 )
						)
				  ),
				  'Tournament' => array(
						'fields' => array(
							 'lan_id',
							 'slug'
						),
				  )
			 ),
			 'order' => array(
				  'TournamentWinner.place = 1' => 'desc',
				  'TournamentWinner.place = 2' => 'desc',
				  'TournamentWinner.place = 3' => 'desc',
			 )
				  ));

		foreach ($teams as $index => $team) {
			$this->Lan->id = $team['Tournament']['lan_id'];
			$this->Lan->read(array('slug'));

			$teams[$index]['Tournament']['Lan']['slug'] = $this->Lan->data['Lan']['slug'];
		}

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

		$tournaments = $this->find('all', array(
			 'conditions' => array(
				  'Tournament.time_start >' => date('Y-m-d H:i:s'),
			 ),
			 'order' => array(
				  'Tournament.time_start' => 'asc'
			 ),
			 'limit' => 4,
			 'contain' => array(
				  'Lan',
				  'Game' => array(
						'Image'
				  )
			 )
				  ));

		foreach ($tournaments as $x => $content) {

			$tournaments[$x]['Tournament']['time_start'] = $this->dateToNice($content['Tournament']['time_start']);

			$tournaments[$x]['Game']['Image']['thumbPath'] = $this->Game->Image->getThumbPath($content['Game']['Image'], '320x180');
		}

		return $tournaments;
	}

	public function getPlacesNotTaken() {
		$places = array(1, 2, 3);

		$places_taken = $this->getPlacesTaken();

		return array_diff($places, $places_taken);
	}

	public function getDataForView() {

		$tournament = $this->find('first', array(
			 'conditions' => array(
				  'Tournament.id' => $this->id
			 ),
			 'fields' => array(
				  'Tournament.id',
				  'Tournament.title',
				  'Tournament.time_start',
				  'Tournament.team_size',
				  'Tournament.slug',
			 ),
			 'contain' => array(
				  'Lan' => array(
						'fields' => array(
							 'title',
							 'slug',
						)
				  ),
				  'Game' => array(
						'fields' => 'image_id'
				  ),
				  'Team' => array(
						'fields' => array(
							 'id'
						)
				  )
			 )
				  ));

		if ($tournament['Game']['image_id']) {
			$tournament['Game'] += $this->Game->Image->find('first', array(
				 'conditions' => array(
					  'Image.id' => $tournament['Game']['image_id']
				 ),
				 'fields' => array(
					  'id',
					  'ext'
				 )
					  ));
		}

		$tournament['Tournament']['time_start_nice'] = $this->dateToNice($tournament['Tournament']['time_start']);

		return $tournament;
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

		$db = $this->getDataSource();
		$total = $db->fetchAll("
			SELECT 
				COUNT(TeamUser.id) AS CountUsers
					FROM `team_users` AS TeamUser
						INNER JOIN `teams` AS Team
							ON TeamUser.team_id = Team.id
				WHERE Team.tournament_id = ? AND TeamUser.user_id = ?", array($this->id, $this->Team->TeamUser->User->id));

		return $total[0][0]['CountUsers'] == 1;

		return $this->Team->find('count', array(
						'conditions' => array(
							 'Team.tournament_id' => $this->id,
						),
						'contain' => array(
							 'TeamUser' => array(
								  'conditions' => array(
										'TeamUser.user_id' => $this->Team->TeamUser->User->id
								  ),
								  'fields' => array(
										'id'
								  )
							 )
						)
				  )) == 1;
	}

	public function isAbleToCreateTeam() {
		if (!$this->isLoggedIn()) {
			return false;
		}

		$this->Team->TeamUser->User->id = $this->getLoggedInId();
		$this->Lan->id = $this->getLanIdByTournamentId($this->id);

		return $this->isSignupOpen() && $this->Lan->isUserAttending() && (!$this->isUserInTournament());
	}

	public function isSignupOpen() {
		$this->read(array('is_signup_open'));

		return $this->data['Tournament']['is_signup_open'];
	}

}

?>
