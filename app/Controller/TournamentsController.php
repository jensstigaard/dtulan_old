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

		if ($this->isAdmin($user)) {
			return true;
		}
		return false;
	}

	public function view($id = null) {

		$this->Tournament->id = $id;

		$this->Tournament->recursive = 2;
		$this->Tournament->Lan->unbindModel(array('hasMany' => array('LanSignup', 'LanDay', 'LanInvite', 'PizzaWave', 'Tournament')));
		$this->Tournament->Game->unbindModel(array('hasMany' => array('Tournament')));

		$this->set('tournament', $this->Tournament->read());
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