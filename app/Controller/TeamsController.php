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

	//put your code here
	public function add($tournament_id = null) {

		if ($tournament_id == null) {
			throw new NotFoundException('Category not found');
		}

		$this->Team->Tournament->id = $tournament_id;

		if (!$this->Team->Tournament->exists()) {
			throw new NotFoundException('Category not found');
		}

		if ($this->request->is('post')) {

			$user = $this->Auth->user();

			$this->request->data['Team']['user_id'] = $user['id'];
			$this->request->data['Team']['tournament_id'] = $tournament_id;

			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash('Your Team has been created.', 'default', array('class' => 'message success'), 'good');
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to create your Team.', 'default', array(), 'bad');
			}
		}

		$this->set('tournament', $this->Team->Tournament->read());
	}

	public function view($id = null) {

		$this->Team->id = $id;

		if (!$this->Team->exists()) {
			throw new NotFoundException('Team not found');
		}

		if ($this->request->is('post')) {
			$this->request->data['TeamInvite']['team_id'] = $id;

			if ($this->Team->TeamInvite->save($this->request->data)) {
				$this->Session->setFlash('Your invites has been sent', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to send your invites', 'default', array(), 'bad');
			}
		}

		$this->Team->recursive = 2;
		$team = $this->Team->read();

		$this->set(compact('team'));
		$this->set('users', $this->Team->getInviteableUsers($id));
	}

	public function deleteInvite($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		$this->Team->TeamInvite->id = $id;

		if (!$this->Team->TeamInvite->exists()) {
			throw new NotFoundException('Invite not found');
		}

		$this->Team->TeamInvite->recursive = 2;
		$invite = $this->Team->TeamInvite->read();

		$leaders = array();

		foreach ($invite['Team']['TeamUser'] as $user) {
			if ($user['is_leader']) {
				$leaders[] = $user['user_id'];
			}
		}

		$current_user = $this->Auth->user();
		if (!in_array($current_user['id'], $leaders)) {
			throw new MethodNotAllowedException('You are not allowed to cancel invites');
		}

//		debug($invite);

		if ($this->Team->TeamInvite->deleteAll(array('TeamInvite.id' => $id), false)) {
			$this->Session->setFlash('Invite cancelled', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('FAILED', 'default', array(), 'bad');
		}

		$this->redirect(array('action' => 'view', $invite['TeamInvite']['team_id']));
	}

}