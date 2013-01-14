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

//	public function edit($lan_id = null) {
//
//		App::uses('CakeTime', 'Utility');
//
//		$this->LanSignup->User->id = $this->Auth->user('id');
//
//		if (!$this->LanSignup->User->exists()) {
//			throw new NotFoundException(__('Invalid user'));
//		}
//
//		$user = $this->LanSignup->User->read();
//
//		$this->LanSignup->Lan->id = $lan_id;
//		if (!$this->LanSignup->Lan->exists()) {
//			throw new NotFoundException('LAN not found');
//		}
//
//		$lan = $this->LanSignup->Lan->read(array('slug'), $lan_id);
//
//		$this->LanSignup->recursive = 2;
//		$lan_signup = $this->LanSignup->find('first', array('conditions' => array(
//				'LanSignup.lan_id' => $lan_id,
//				'LanSignup.user_id' => $user['User']['id']
//			)
//				)
//		);
//
//		$id = $lan_signup['LanSignup']['id'];
//
//		if (!$lan_signup) {
//			throw new InvalidArgumentException('You are not signed up for this LAN');
//		}
//
//		if ($this->request->is('post')) {
//
//			$days_wanted = array();
//			foreach ($this->request->data['LanSignupDay'] as $day_id => $day_value) {
//				if ($day_value['lan_day_id'] > 0) {
//					$days_wanted[$day_value['lan_day_id']] = $day_value['lan_day_id'];
//				}
//			}
//
//			unset($this->request->data['LanSignupDay']);
//
//			$days_delete = array();
//			foreach ($lan_signup['LanSignupDay'] as $day) {
//				if (!in_array($day['lan_day_id'], $days_wanted)) {
//					$days_delete[] = $day['id'];
//				} else {
//					unset($days_wanted[$day['lan_day_id']]);
//				}
//			}
//
//			foreach ($days_wanted as $day_wanted) {
//				$this->request->data[] = array(
//					'lan_day_id' => $day_wanted,
//					'lan_signup_id' => $id
//				);
//			}
//
//			if (count($lan_signup['LanSignupDay']) + count($days_wanted) - count($days_delete) == 0) {
//				$this->Session->setFlash('No days selected!', 'default', array(), 'bad');
//			} else {
//				if (count($days_delete)) {
//					if (!$this->LanSignup->LanSignupDay->deleteAll(array('LanSignupDay.id' => $days_delete), false)) {
//						$this->Session->setFlash('Dates were NOT DELETED', 'default', array(), 'bad');
//						$not_ok = 1;
//					}
//				}
//
////				$this->LanSignup->id = $id;
//
//				if (count($days_wanted) && !isset($not_ok)) {
//					if ($this->LanSignup->LanSignupDay->saveMany($this->request->data)) {
//						$saved = 1;
//					} else {
//						$this->Session->setFlash('Unable to add your signup. Have You selected any days?', 'default', array(), 'bad');
//					}
//				}
//
//				if (isset($saved)) {
//					$this->Session->setFlash('Your signup has been updated', 'default', array('class' => 'message success'), 'good');
//					$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
//				}
//			}
//		}
//
//		// Load updated data
//		$lan_signup2 = $this->LanSignup->read(null, $id);
//
//		$days_selected = array();
//		foreach ($lan_signup2['LanSignupDay'] as $day) {
//			$days_selected[] = $day['lan_day_id'];
//		}
//
//
//		$lan_days = array();
//		foreach ($lan_signup2['Lan']['LanDay'] as $lan_day) {
//			$seats_left = $this->LanSignup->LanSignupDay->LanDay->seatsLeft($lan_day['id']);
//
//			$lan_days[CakeTime::format('Y-m-d', $lan_day['date'])] = array(
//				'id' => $lan_day['id'],
//				'value' => CakeTime::format('D, M jS Y', $lan_day['date']),
//				'seats_left' => $seats_left,
//				'checked' => in_array($lan_day['id'], $days_selected)
//			);
//		}
//
//		ksort($lan_days);
//
//		$this->set(compact('lan_signup', 'lan_days', 'user'));
//	}

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

		$update_balances = array();
		$update_balances[] = array(
			'id' => $user['User']['id'],
			'balance' => $user['User']['balance'] + $lan_signup['Lan']['price']
		);

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

					$update_balances[] = array(
						'id' => $invite['Guest']['id'],
						'balance' => $invite['Guest']['balance'] + $lan_signup['Lan']['price'],
					);
				}
			}
		} else {
			$lan_invite = $this->LanSignup->LanInvite->find('first', array(
				'conditions' => array(
					'LanInvite.lan_signup_id' => $id
				)
					)
			);
			$delete_lan_invites[] = $lan_invite['LanInvite']['id'];
		}


		if (!$this->LanSignup->User->saveMany($update_balances)) {
			$this->Session->setFlash('Saving your balance failed', 'default', array(), 'bad');
		} else {
			if (!(
					$this->LanSignup->deleteAll(array(
						'LanSignup.id' => $delete_lan_signups
					)) && $this->LanSignup->LanSignupDay->deleteAll(array(
						'LanSignupDay.lan_signup_id' => $delete_lan_signups
					))
					)
			) {
				$this->Session->setFlash('Your signup could not be deleted', 'default', array(), 'bad');
				$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
			} else {
				if (count($delete_lan_invites)) {
					$this->LanSignup->LanInvite->deleteAll(array('LanInvite.id' => $delete_lan_invites));
				}
				$this->Session->setFlash('Your signup has been deleted', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
			}
		}
	}

}

?>
