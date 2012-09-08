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

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function add($id = null) {

		App::uses('CakeTime', 'Utility');

		$user = $this->Auth->user();

		$this->LanSignup->User->id = $user['id'];

		if (!$this->LanSignup->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->LanSignup->User->read();

		if ($user['User']['type'] == 'guest') {
			if ($this->LanSignup->Lan->LanInvite->find('count', array('conditions' => array(
							'LanInvite.lan_id' => $id,
							'LanInvite.user_guest_id' => $user['User']['id'],
							'LanInvite.accepted' => 0
						)
							)
					)
					!= 1) {
				throw new BadRequestException('Invite not found');
			}
		}

		$this->LanSignup->Lan->id = $id;
		if (!$this->LanSignup->Lan->exists()) {
			throw new NotFoundException('LAN not found');
		}

		$lans = array();
		foreach ($user['LanSignup'] as $lan) {
			$lans[] = $lan['lan_id'];
		}

		if (in_array($id, $lans)) {
			throw new BadRequestException(__('LAN already signed up'));
		}

		$lan = $this->LanSignup->Lan->read();

		if ($this->request->is('post')) {

			foreach ($this->request->data['LanSignupDay'] as $day_id => $day_value) {
				if ($day_value['lan_day_id'] == 0) {
					unset($this->request->data['LanSignupDay'][$day_id]);
				}
			}

			if (count($this->request->data['LanSignupDay'])) {
				$this->request->data['LanSignup']['lan_id'] = $id;
				$this->request->data['LanSignup']['user_id'] = $user['User']['id'];

				if ($user['User']['type'] == 'guest') {
					$this->LanSignup->LanInvite->recursive = 0;
					$invite = $this->LanSignup->LanInvite->find('first', array('conditions' => array(
							'LanInvite.lan_id' => $id,
							'LanInvite.user_guest_id' => $user['User']['id'],
							'LanInvite.accepted' => 0
						)
							)
					);

					$this->request->data['LanInvite'] = array('id' => $invite['LanInvite']['id'], 'accepted' => 1);
				}
			}


			if ($this->LanSignup->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your signup has been saved.');
				$this->redirect(array('controller' => 'users', 'action' => 'profile'));
			} else {
				$this->Session->setFlash('Unable to add your signup. Have You selected any days?');
			}
		}

		$lan_days = array();
		foreach ($lan['LanDay'] as $lan_day) {
			$seats_left = $this->LanSignup->LanSignupDay->LanDay->seatsLeft($lan_day['id']);

			$lan_days[$lan_day['id']] = array(
				'value' => CakeTime::format('D, M jS Y', $lan_day['date']),
				'seats_left' => $seats_left
			);
		}

		$this->set(compact('lan', 'lan_days', 'user'));
	}

}

?>
