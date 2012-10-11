<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeamController
 *
 * @author Superkatten
 */
class TeamsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin()) {
			return true;
		}
		return false;
	}

	public function add($tournament_id = null) {

		if ($tournament_id == null) {
			throw new NotFoundException('Team not found');
		}

		$this->Team->Tournament->id = $tournament_id;

		if (!$this->Team->Tournament->exists()) {
			throw new NotFoundException('Team not found');
		}

		if ($this->request->is('post')) {

			$this->request->data['Team']['tournament_id'] = $tournament_id;
			$this->request->data['TeamUser']['user_id'] = $this->Auth->user('id');
			$this->request->data['TeamUser']['is_leader'] = 1;


			if ($this->Team->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your team has been created', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to create your team', 'default', array(), 'bad');
			}
		}

		$this->set('tournament', $this->Team->Tournament->read());
	}

	public function view($id = null) {

		$this->Team->id = $id;

		if (!$this->Team->exists()) {
			throw new NotFoundException('Team not found');
		}

		$this->Team->recursive = 2;

		$this->set('team', $this->Team->read());

		$this->set('is_leader', $this->Auth->loggedIn() && $this->Team->isLeader($id, $this->Auth->user('id')));

		$this->set('users', $this->Team->getInviteableUsers($id));
	}
}