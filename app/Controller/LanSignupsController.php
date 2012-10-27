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

	public function index($lan_id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->LanSignup->Lan->id = $lan_id;
		if (!$this->LanSignup->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $lan_id);
		}

		$this->paginate = array(
			'LanSignup' => array(
				'conditions' => array(
					'LanSignup.lan_id' => $lan_id,
					'NOT' => array(
						'LanSignup.id' => $this->LanSignup->getLanSignupsCrewIds($lan_id)
					)
				),
				'recursive' => 2,
				'limit' => 10,
				'order' => array(
					array('User.name' => 'asc')
				)
			),
		);

		$lan_signups = $this->paginate('LanSignup');

		$this->set(compact('lan_signups'));
	}

	public function index_crew($lan_id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->LanSignup->Lan->id = $lan_id;
		if (!$this->LanSignup->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $lan_id);
		}

		$this->set('lan_signups_crew', $this->LanSignup->getLanSignupsCrew($lan_id));
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

		if ($this->request->is('post') && isset($this->request->data['LanSignupDay']) && count($this->request->data['LanSignupDay'])) {

			foreach ($this->request->data['LanSignupDay'] as $day_id => $day_value) {
				if ($day_value['lan_day_id'] == 0) {
					unset($this->request->data['LanSignupDay'][$day_id]);
				} elseif (!$this->LanSignup->LanSignupDay->LanDay->seatsLeft($day_value['lan_day_id'])) {
					$do_not_save = 1;
				}
			}

			if (isset($do_not_save)) {
				$this->Session->setFlash('A day selected which is not available anymore', 'default', array(), 'bad');
			} else {
				$this->request->data['LanSignup']['lan_id'] = 0;

				if (count($this->request->data['LanSignupDay'])) {
					$this->request->data['LanSignup']['lan_id'] = $id;

					if ($user['User']['type'] == 'guest') {
						$invite = $this->LanSignup->LanInvite->find('first', array('conditions' => array(
								'LanInvite.lan_id' => $id,
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
				}

				$this->request->data['User']['id'] = $user['User']['id'];
				$this->request->data['User']['balance'] = $user['User']['balance'] - $lan['Lan']['price'];

				if ($this->LanSignup->saveAssociated($this->request->data)) {
					$this->Session->setFlash('Your signup has been saved', 'default', array('class' => 'message success'), 'good');
					$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan['Lan']['slug']));
				} else {
					$this->Session->setFlash('Unable to add your signup. Have You selected any days?', 'default', array(), 'bad');
					debug($this->LanSignup->validationErrors);
					debug($this->request->data);
				}
			}
		}

		$lan_days = array();
		foreach ($lan['LanDay'] as $lan_day) {
			$seats_left = $this->LanSignup->LanSignupDay->LanDay->seatsLeft($lan_day['id']);

			$lan_days[CakeTime::format('Y-m-d', $lan_day['date'])] = array(
				'id' => $lan_day['id'],
				'value' => CakeTime::format('D, M jS Y', $lan_day['date']),
				'seats_left' => $seats_left,
			);

			ksort($lan_days);
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
				$this->request->data[] = array(
					'lan_day_id' => $day_wanted,
					'lan_signup_id' => $id
				);
			}

			if (count($lan_signup['LanSignupDay']) + count($days_wanted) - count($days_delete) == 0) {
				$this->Session->setFlash('No days selected!', 'default', array(), 'bad');
			} else {
				if (count($days_delete)) {
					if (!$this->LanSignup->LanSignupDay->deleteAll(array('LanSignupDay.id' => $days_delete), false)) {
						$this->Session->setFlash('Dates were NOT DELETED', 'default', array(), 'bad');
						$not_ok = 1;
					}
				}

//				$this->LanSignup->id = $id;

				if (count($days_wanted) && !isset($not_ok)) {
					if ($this->LanSignup->LanSignupDay->saveMany($this->request->data)) {
						$saved = 1;
					} else {
						$this->Session->setFlash('Unable to add your signup. Have You selected any days?', 'default', array(), 'bad');
					}
				}

				if (isset($saved)) {
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

			$lan_days[CakeTime::format('Y-m-d', $lan_day['date'])] = array(
				'id' => $lan_day['id'],
				'value' => CakeTime::format('D, M jS Y', $lan_day['date']),
				'seats_left' => $seats_left,
				'checked' => in_array($lan_day['id'], $days_selected)
			);
		}

		ksort($lan_days);

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
