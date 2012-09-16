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


		$this->LanSignup->User->id = $this->Auth->user('id');

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

		$this->LanSignup->Lan->recursive = 2;
		$lan = $this->LanSignup->Lan->read();

		if ($this->request->is('post')) {

			foreach ($this->request->data['LanSignupDay'] as $day_id => $day_value) {
				if ($day_value['lan_day_id'] == 0) {
					unset($this->request->data['LanSignupDay'][$day_id]);
				}
			}

			$this->request->data['LanSignup']['lan_id'] = 0;
			$this->request->data['LanSignup']['user_id'] = 0;

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

			$this->request->data['User']['id'] = $user['User']['id'];
			$this->request->data['User']['balance'] = $user['User']['balance'] - $lan['Lan']['price'];

			if ($this->LanSignup->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your signup has been saved', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
			} else {
				$this->Session->setFlash('Unable to add your signup. Have You selected any days?', 'default', array(), 'bad');
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

	public function edit($lan_id = null) {

		App::uses('CakeTime', 'Utility');

		$this->LanSignup->User->id = $this->Auth->user('id');

		if (!$this->LanSignup->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->LanSignup->User->read();

		$this->LanSignup->Lan->id = $lan_id;
		if (!$this->LanSignup->Lan->exists()) {
			throw new NotFoundException('LAN not found');
		}

		$lan = $this->LanSignup->Lan->read(array('slug'), $lan_id);

		$this->LanSignup->recursive = 2;
		$lan_signup = $this->LanSignup->find('first', array('conditions' => array(
				'LanSignup.lan_id' => $lan_id,
				'LanSignup.user_id' => $user['User']['id']
			)
				)
		);

		$id = $lan_signup['LanSignup']['id'];

		if (!$lan_signup) {
			throw new InvalidArgumentException('You are not signed up for this LAN');
		}

		if ($this->request->is('post')) {

			$days_wanted = array();
			foreach ($this->request->data['LanSignupDay'] as $day_id => $day_value) {
				if ($day_value['lan_day_id'] > 0) {
					$days_wanted[$day_value['lan_day_id']] = $day_value['lan_day_id'];
				}
			}

			unset($this->request->data['LanSignupDay']);

			$days_delete = array();
			foreach ($lan_signup['LanSignupDay'] as $day) {
				if (!in_array($day['lan_day_id'], $days_wanted)) {
					$days_delete[] = $day['id'];
				} else {
					unset($days_wanted[$day['lan_day_id']]);
				}
			}

			foreach ($days_wanted as $day_wanted) {
				$this->request->data['LanSignupDay'][]['lan_day_id'] = $day_wanted;
			}

			if (count($lan_signup['LanSignupDay']) + count($days_wanted) - count($days_delete) == 0) {
				$this->Session->setFlash('No days selected!', 'default', array(), 'bad');
			} else {
				if (count($days_delete)) {
					if (!$this->LanSignup->LanSignupDay->deleteAll(array('LanSignupDay.id' => $days_delete), false)) {
						$this->Session->setFlash('Dates were NOT DELETED', 'default', array(), 'bad');
						$not_ok = 1;
					} elseif (!count($days_wanted)) {
						$saved = 1;
					}
				}

				$this->request->data['LanSignup']['id'] = $id;

				if (count($days_wanted) && !isset($not_ok)) {
					if ($this->LanSignup->saveAssociated($this->request->data)) {
						$saved = 1;
					} else {
						$this->Session->setFlash('Unable to add your signup. Have You selected any days?', 'default', array(), 'bad');
					}
				}

				if ($saved) {
					$this->Session->setFlash('Your signup has been updated', 'default', array('class' => 'message success'), 'good');
					$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
				}
			}
		}

		// Load updated data
		$lan_signup2 = $this->LanSignup->read(null, $id);

		$days_selected = array();
		foreach ($lan_signup2['LanSignupDay'] as $day) {
			$days_selected[] = $day['lan_day_id'];
		}


		$lan_days = array();
		foreach ($lan_signup2['Lan']['LanDay'] as $lan_day) {
			$seats_left = $this->LanSignup->LanSignupDay->LanDay->seatsLeft($lan_day['id']);

			$lan_days[] = array(
				'id' => $lan_day['id'],
				'value' => CakeTime::format('D, M jS Y', $lan_day['date']),
				'seats_left' => $seats_left,
				'checked' => in_array($lan_day['id'], $days_selected)
			);
		}

		$this->set(compact('lan_signup', 'lan_days', 'user'));
	}

	public function delete($lan_id = null) {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Bad request from client');
		}

		$this->LanSignup->User->id = $this->Auth->user('id');

		if (!$this->LanSignup->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->LanSignup->User->read();

		$this->LanSignup->Lan->id = $lan_id;
		if (!$this->LanSignup->Lan->exists()) {
			throw new NotFoundException('LAN not found');
		}

		$lan = $this->LanSignup->Lan->read(array('slug'), $lan_id);

		$this->LanSignup->recursive = 2;
		$lan_signup = $this->LanSignup->find('first', array('conditions' => array(
				'LanSignup.lan_id' => $lan_id,
				'LanSignup.user_id' => $user['User']['id']
			)
				)
		);

		$id = $lan_signup['LanSignup']['id'];

		if (!$lan_signup) {
			throw new InvalidArgumentException('You are not signed up for this LAN');
		}

		$delete_lan_signups = array();
		$delete_lan_invites = array();

		$delete_lan_signups[] = $id;

		if ($user['User']['type'] == 'student') {

			$lan_invites = $this->LanSignup->LanInvite->find('all', array('conditions' => array(
					'LanInvite.lan_id' => $lan_signup['LanSignup']['lan_id'],
					'LanInvite.user_student_id' => $lan_signup['LanSignup']['user_id'],
				)
					)
			);

			foreach ($lan_invites as $invite) {
				$delete_lan_invites[] = $invite['LanInvite']['id'];

				if ($invite['LanInvite']['accepted']) {
					$delete_lan_signups[] = $invite['LanInvite']['lan_signup_id'];
				}
			}
		} else {
			$delete_lan_invites[] = $id;
		}

		$this->LanSignup->User->id = $user['User']['id'];
		$this->request->data['User']['id'] = $user['User']['id'];
		$this->request->data['User']['balance'] = $user['User']['balance'] + $lan_signup['Lan']['price'];

		if ($this->LanSignup->deleteAll(array('LanSignup.id' => $delete_lan_signups)) && $this->LanSignup->LanSignupDay->deleteAll(array('lan_signup_id' => $id)) && $this->LanSignup->User->save($this->request->data, false)) {

			if (count($delete_lan_invites)) {
				$this->LanSignup->LanInvite->deleteAll(array('lan_signup_id' => $delete_lan_invites));
			}

			$this->Session->setFlash('Your signup has been deleted', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
		} else {
			$this->Session->setFlash('Your signup could not be deleted', 'default', array(), 'bad');
			$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan_id));
		}
	}

}

?>
