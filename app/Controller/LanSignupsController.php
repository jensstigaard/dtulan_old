<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignupsController
 *
 * @author Jens
 */
class LanSignupsController extends AppController {

	public $components = array('RequestHandler');
	public $helpers = array('Js');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function add($lan_id = null) {

		App::uses('CakeTime', 'Utility');

		$this->LanSignup->User->id = $this->Auth->user('id');

		if (!$this->LanSignup->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->LanSignup->User->read();

		if ($user['User']['type'] == 'guest' && $this->LanSignup->Lan->LanInvite->isNotAccepted($lan_id, $user['User']['id'])) {
			throw new BadRequestException('Invite not found');
		}

		$this->LanSignup->Lan->id = $lan_id;

		if (!$this->LanSignup->Lan->exists()) {
			throw new NotFoundException('LAN not found');
		}

		if ($this->LanSignup->Lan->isUserAttending()) {
			throw new BadRequestException(__('LAN already signed up'));
		}

		$lan = $this->LanSignup->Lan->read();

		if ($this->request->is('post')) {

			$this->request->data['LanSignup']['lan_id'] = $lan_id;

			if ($user['User']['type'] == 'guest') {
				$invite = $this->LanSignup->LanInvite->find('first', array('conditions' => array(
						'LanInvite.lan_id' => $lan_id,
						'LanInvite.user_guest_id' => $user['User']['id'],
						'LanInvite.accepted' => 0
					),
					'recursive' => 0
						)
				);

				$this->request->data['LanInvite'] = array(
					'id' => $invite['LanInvite']['id'],
					'accepted' => 1
				);
			}

			$this->request->data['User']['id'] = $user['User']['id'];
			$this->request->data['User']['balance'] = $user['User']['balance'] - $lan['Lan']['price'];

			$this->request->data['LanSignupCode'] = array(
				'id' => $this->LanSignup->LanSignupCode->getIdByCode($this->request->data['LanSignup']['code']),
				'accepted' => 1,
			);

			if ($this->LanSignup->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your signup has been saved', 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
			} else {
				$this->Session->setFlash('Unable to add your signup', 'default', array(), 'bad');
			}
		}

		$this->set(compact('lan'));
	}

	public function delete($lan_id = null) {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Bad request from client');
		}

		$this->LanSignup->User->id = $this->Auth->user('id');
		$this->LanSignup->Lan->id = $lan_id;

		if (!$this->LanSignup->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->LanSignup->User->read(array('id', 'balance'));

		if (!$this->LanSignup->Lan->exists()) {
			throw new NotFoundException('LAN not found');
		}

		if (!$this->LanSignup->isSignupOpen()) {
			throw new UnauthorizedException('Unable to delete signup when signup is not open.');
		}

		$lan_signup = $this->LanSignup->getDataForDeletion();

		$this->LanSignup->id = $lan_signup['LanSignup']['id'];
		$new_balance = $user['User']['balance'] + $lan_signup['Lan']['price'];

		$this->LanSignup->Lan->read(array('slug'));

		if (
				$this->LanSignup->User->saveField('balance', $new_balance, true)
				&&
				$this->LanSignup->delete()
		) {
			$this->Session->setFlash('Your signup has been deleted', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
		} else {
			$this->Session->setFlash('Your signup could not be deleted', 'default', array(), 'bad');
			$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
		}
	}

}

?>
