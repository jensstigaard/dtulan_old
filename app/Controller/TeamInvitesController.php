<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeamInvitesController
 *
 * @author Jens
 */
class TeamInvitesController extends AppController {

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

	public function add() {
		if (!$this->request->is('post')) {
			throw new BadRequestException;
		}

		if ($this->TeamInvite->save($this->request->data)) {
			$this->Session->setFlash('Your invite has been sent', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Unable to send your invites', 'default', array(), 'bad');
		}
		$this->redirect(array('controller' => 'teams', 'action' => 'view', $this->request->data['TeamInvite']['team_id']));
	}

	public function delete($id) {
		if (!$this->request->is('post')) {
			throw new BadRequestException;
		}

		$this->TeamInvite->id = $id;

		if (!$this->TeamInvite->exists()) {
			throw new NotFoundException('Invite not found');
		}

		$this->TeamInvite->recursive = 2;
		$this->TeamInvite->read();

		$team_id = $this->TeamInvite->data['Team']['id'];

		if (!$this->TeamInvite->Team->isLeader($team_id, $this->Auth->user('id'))) {
			throw new MethodNotAllowedException('You are not allowed to cancel invites');
		}

		if ($this->TeamInvite->delete()) {
			$this->Session->setFlash('Invite cancelled', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('FAILED to cancel team invite', 'default', array(), 'bad');
		}

		$this->redirect(array('controller' => 'teams', 'action' => 'view', $team_id));
	}

}

?>
