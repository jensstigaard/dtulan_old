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

		$this->Tournament->id = $this->Tournament->getTournamentIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found with id #' . $id);
		}

		$tournament = $this->Tournament->read();
		$tournament['Tournament']['time_start_nice'] = $this->Tournament->dateToNice($tournament['Tournament']['time_start']);

		$this->Tournament->Lan->id = $tournament['Tournament']['lan_id'];

		$lan = $this->Tournament->Lan->read(array('slug', 'title'));

		$this->set(compact('tournament', 'lan'));
		
		$this->set('winner_teams', $this->Tournament->getWinnerTeams());
	}

	public function view_description($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getTournamentIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('tournament', $this->Tournament->read(array('description')));
	}

	public function view_rules($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getTournamentIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('tournament', $this->Tournament->read(array('rules')));
	}

	public function view_bracket($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getTournamentIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('tournament', $this->Tournament->read(array('bracket')));
	}

	public function view_teams($lan_slug, $tournament_slug) {

		$this->layout = 'ajax';

		$this->Tournament->id = $this->Tournament->getTournamentIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		$this->set('teams', $this->Tournament->Team->find('all', array(
						'conditions' => array(
							 'Team.tournament_id' => $this->Tournament->id
						),
						'recursive' => 1,
						'order' => array(
							 'Team.place = 1' => 'desc',
							 'Team.place = 2' => 'desc',
							 'Team.place = 3' => 'desc',
						)
				  )));
	}

	public function add($lan_id) {

		$this->Tournament->Lan->id = $lan_id;

		if (!$this->Tournament->Lan->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$this->set('lan', $this->Tournament->Lan->read());

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