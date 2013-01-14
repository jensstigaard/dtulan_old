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

		if (in_array($this->action, array('view')) || $this->isAdmin($user)) {
			return true;
		}
		return false;
	}

	public function view($id = null) {

		$this->Tournament->id = $id;

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found with id #' . $id);
		}

		$this->Tournament->recursive = 1;

		$tournament = $this->Tournament->read();
		$tournament['Tournament']['time_start_nice'] = $this->Tournament->dateToNice($tournament['Tournament']['time_start']);

		$this->Tournament->Lan->id = $tournament['Tournament']['lan_id'];

		$lan = $this->Tournament->Lan->read(array('slug', 'title'));

		$this->set(compact('tournament', 'lan'));
	}

	public function view_description($id) {
		$this->Tournament->id = $id;

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found with id #' . $id);
		}

		$this->layout = 'ajax';

		$this->set('tournament', $this->Tournament->read(array('description')));
	}

	public function view_rules($id) {
		$this->Tournament->id = $id;

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found with id #' . $id);
		}

		$this->layout = 'ajax';

		$this->set('tournament', $this->Tournament->read(array('rules')));
	}

	public function view_bracket($id) {
		$this->Tournament->id = $id;

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found with id #' . $id);
		}

		$this->layout = 'ajax';

		$this->set('tournament', $this->Tournament->read(array('bracket')));
	}

	public function view_teams($id) {
		$this->Tournament->id = $id;

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found with id #' . $id);
		}

		$this->layout = 'ajax';

		$this->set('teams', $this->Tournament->Team->find('all', array(
					'conditions' => array(
						'Team.tournament_id' => $id
					),
					'recursive' => 1,
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

	public function edit($tournament_id) {
		$this->Tournament->id = $tournament_id;

		if (!$this->Tournament->exists()) {
			throw new NotFoundException('Tournament not found');
		}

		$this->set(compact('tournament_id'));

		if ($this->request->is('get')) {
			$this->request->data = $this->Tournament->read();
		} else {
			if ($this->Tournament->save($this->request->data)) {
				$this->Session->setFlash('Tournament has been updated.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'view', $tournament_id));
			} else {
				$this->Session->setFlash('Unable to update tournament.', 'default', array(), 'bad');
			}
		}
	}

}