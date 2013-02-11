<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TournamentsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if (in_array($this->action, array(
						'view',
						'view_description',
						'view_bracket',
						'view_rules',
						'view_teams'
				  )) || $this->Tournament->isYouAdmin($user)) {
			return true;
		}
		return false;
	}

	public function view($lan_slug, $tournament_slug) {

		$this->Tournament->id = $this->Tournament->getIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$tournament = $this->Tournament->find('first', array(
			 'conditions' => array(
				  'Tournament.id' => $this->Tournament->id
			 ),
			 'fields' => array(
				  'id',
				  'title',
				  'time_start',
				  'team_size',
				  'slug'
			 ),
			 'contain' => array(
				  'Lan' => array(
						'fields' => array(
							 'title',
							 'slug',
						)
				  ),
				  'Game' => array(
						'Image' => array(
							 'fields' => array(
								  'id',
								  'ext'
							 )
						),
				  )
			 )
				  ));

		$tournament['Tournament']['time_start_nice'] = $this->Tournament->dateToNice($tournament['Tournament']['time_start']);

		$this->set(compact('tournament'));

		$this->set('winner_teams', $this->Tournament->getWinnerTeams());
		$this->set('title_for_layout', $tournament['Tournament']['title']. ' &bull; '. $tournament['Lan']['title']);
		$this->set('can_create_team', $this->Tournament->isAbleToCreateTeam());
		
	}

	public function view_description($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('tournament', $this->Tournament->read(array('description')));
	}

	public function view_rules($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('tournament', $this->Tournament->read(array('rules')));
	}

	public function view_bracket($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('tournament', $this->Tournament->read(array('bracket')));
	}

	public function view_teams($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('teams', $this->Tournament->getTeamsList());
	}

	public function add($lan_id) {

		$this->Tournament->Lan->id = $lan_id;

		if (!$this->Tournament->Lan->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$this->set('lan', $this->Tournament->Lan->read(array('title')));

		if ($this->request->is('post')) {

			$this->request->data['Tournament']['lan_id'] = $lan_id;

			if ($this->Tournament->save($this->request->data)) {
				$this->Session->setFlash('Your Tournament has been created', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'view', $this->Tournament->getLastInsertID()));
			} else {
				$this->Session->setFlash('Unable to create your tournament', 'default', array(), 'bad');
			}
		}

		$this->set('games', $this->Tournament->Game->find('list'));
	}

	public function edit($id) {
		$this->Tournament->id = $id;

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found with id: ' . $id);
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->Tournament->read();
		} else {
			if ($this->Tournament->save($this->request->data)) {
				$this->Session->setFlash('Tournament has been updated', 'default', array('class' => 'message success'), 'good');

				$this->Tournament->read(array('Tournament.slug', 'Lan.slug'));

				$this->redirect(array('action' => 'view', $this->Tournament->data['Lan']['slug'], $this->Tournament->data['Tournament']['slug']));
			} else {
				$this->Session->setFlash('Unable to update tournament', 'default', array(), 'bad');
			}
		}

		$this->Tournament->read(array('slug', 'lan_id'));
		$this->Tournament->Lan->read('slug', $this->Tournament->data['Tournament']['lan_id']);

		$this->set('tournament_slug', $this->Tournament->data['Tournament']['slug']);
		$this->set('lan_slug', $this->Tournament->Lan->data['Lan']['slug']);
	}

}