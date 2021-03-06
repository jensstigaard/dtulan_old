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

		if (in_array($this->action, array('view', 'add', 'delete')) || $this->isAdmin()) {
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

			$this->request->data['TeamUser'] = array(
				0 => array(
					'user_id' => $this->Auth->user('id'),
					'is_leader' => 1,
					'Team' => array(
						'tournament_id' => $tournament_id
					)
				)
			);


			if ($this->Team->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your team has been created', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('controller' => 'teams', 'action' => 'view', $this->Team->getInsertID()));
			} else {
				$errors = $this->Team->invalidFields();

				if (isset($errors['TeamUser'][0]['user_id'][0])) {
					$this->Session->setFlash($errors['TeamUser'][0]['user_id'][0], 'default', array(), 'bad');
				} else {
					$this->Session->setFlash('Unable to create your team', 'default', array(), 'bad');
				}
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

	public function delete($id) {
		$this->Team->id = $id;

		if (!$this->Team->exists()) {
			throw new NotFoundException('Team not found');
		}

		$team = $this->Team->read('tournament_id');

		if (!$this->Team->isLeader($id, $this->Auth->user('id'))) {
			throw new UnauthorizedException('You are not allowed to do this');
		}

		if ($this->Team->delete()) {
			$this->Session->setFlash('Team deleted', 'default', array(), 'bad');
		} else {
			$this->Session->setFlash('Unable to delete your team', 'default', array('class' => 'message success'), 'good');
		}

		$this->redirect(array('controller' => 'tournaments', 'action' => 'view', $team['Team']['tournament_id']));
	}

}