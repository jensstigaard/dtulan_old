<?php

class LansController extends AppController {

	public $helpers = array('Html', 'Form');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)
//				|| in_array($this->action, array('view'))
		) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->set('lans', $this->Lan->find('all'));
	}

//	public function viewPastLans() {
//		$currentTime = date('Y-m-d H:i:s');
//		$data = $this->Lan->find('all', array(
//			'conditions' => array(
//				'Lan.published' => 1,
//				'Lan.time_end <' => $currentTime
//				)));
//
//		$this->set('lans', $data);
//		$this->view = 'index';
//	}
//
//	public function viewCurrentLans() {
//		$currentTime = date('Y-m-d H:i:s');
//		$data = $this->Lan->find('all', array(
//			'conditions' => array(
//				'Lan.time_end >' => $currentTime,
//				'Lan.time_start <' => $currentTime
//				)));
//		$this->set('lans', $data);
//		$this->view = 'index';
//	}
//
//	public function viewFutureLans() {
//		$currentTime = date('Y-m-d H:i:s');
//		$data = $this->Lan->find('all', array(
//			'conditions' => array(
//				'Lan.time_start >' => $currentTime
//				)));
//		$this->set('lans', $data);
//		$this->view = 'index';
//	}

	public function view($id = null) {

		$cond['Lan.id'] = $id;
		if ($this->Auth->loggedIn()) {
			$user = $this->Auth->user();
			if (!$this->isAdmin($user)) {
				$cond['Lan.published'] = 1;
			}
		}
		$lan = $this->Lan->find('first', array('conditions' => $cond));

		if (!isset($lan['Lan'])) {
			throw new NotFoundException('No LAN found');
		}

		$title_for_layout = 'Lan &bull; ' . $lan['Lan']['title'];

		$this->set(compact('lan', 'title_for_layout'));


		$this->set('lan_days', $this->Lan->LanDay->find('all', array(
					'conditions' => array(
						'LanDay.lan_id' => $id
					),
					'order' => array(
						'LanDay.date ASC',
					)
						)
				)
		);

		$this->set('lan_invites', $this->Lan->LanInvite->find('all', array(
					'conditions' => array(
						'LanInvite.lan_id' => $id
					)
						)
				)
		);


		$this->Lan->LanSignup->recursive = 2;
		$this->Lan->LanSignup->unbindModel(array(
			'belongsTo' => array(
				'Lan'
			),
			'hasOne' => array(
				'LanInvite'
			),
			'hasMany' => array(
//				'LanSignupDay'
			)
				)
		);
		$this->Lan->LanSignup->User->unbindModel(array(
			'hasOne' => array(
				'UserPasswordTicket'
			),
			'hasMany' => array(
				'LanSignup',
				'LanInvite',
				'LanInviteSent',
				'Payment',
				'PizzaOrder',
				'TeamInvite',
				'TeamUser'
			)
		));

		$this->paginate = array(
			'LanSignup' => array(
				'conditions' => array(
					'LanSignup.lan_id' => $id,
				),
				'limit' => 10,
				'order' => array(
					array('User.name' => 'asc')
				)
			)
		);

		$this->set('lan_signups', $this->paginate('LanSignup'));


		if ($lan['Lan']['sign_up_open'] && isset($user)) {

			if ($user['type'] == 'student') {
				if (!$this->Lan->isUserAttending($id, $user['id'])) {
					$this->set('is_not_attending', 1);
				} else {
					if ($this->request->is('post')) {
						$this->request->data['LanInvite']['lan_id'] = $id;
						$this->request->data['LanInvite']['user_student_id'] = $user['id'];
						$this->request->data['LanInvite']['time_invited'] = date('Y-m-d H:i:s');

						if ($this->Lan->LanInvite->save($this->request->data)) {
							$this->Session->setFlash('Your invite has been sent', 'default', array('class' => 'message success'), 'good');
						} else {
							$this->Session->setFlash('Unable to send your invite', 'default', array(), 'bad');
						}
					}

					$user_guests = $this->Lan->getInviteableUsers($id, $user['id']);
					$this->set(compact('user_guests'));
				}
			}
		}
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->request->data['LanDay'] = $this->Lan->getLanDays($this->request->data['Lan']['time_start'], $this->request->data['Lan']['time_end']);


			if ($this->Lan->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your Lan has been saved.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your lan.', 'default', array(), 'bad');
			}
		}
	}

	public function edit($id = null) {
		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException(__('Invalid Lan'));
		}

		if ($this->request->is('post')) {
			if ($this->Lan->save($this->request->data)) {
				$this->Session->setFlash(__('The LAN has been saved'), 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Lan could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
		} else {
			$this->request->data = $this->Lan->read(null, $id);
		}
	}

	public function openForSignup($id = null) {

		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException(__('Invalid Lan'));
		}

		if (!$this->request->is('post')) {
			throw new BadRequestException('Bad request');
		}

		$this->request->data['Lan']['sign_up_open'] = 1;

		if ($this->Lan->save($this->request->data)) {
			$this->Session->setFlash(__('The LAN has been opened for signups'), 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash(__('The Lan could not be saved. Please try again'), 'default', array(), 'bad');
		}
		$this->redirect($this->referer());
	}

}