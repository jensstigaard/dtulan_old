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
		$this->Lan->recursive = 1;
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

	public function view($slug) {

		$cond = array('Lan.slug' => $slug);

		if ($this->Auth->loggedIn()) {
			$user = $this->Auth->user();
			if (!$this->Lan->LanSignup->User->isAdmin($user)) {
				$cond['published'] = 1;
			}
		}
		$lan = $this->Lan->find('first', array('conditions' => $cond));

		if (!$lan) {
			throw new NotFoundException('No LAN found');
		}

		$lan_id = $lan['Lan']['id'];

		$title_for_layout = 'Lan &bull; ' . $lan['Lan']['title'];

		$this->set(compact('lan', 'title_for_layout'));


		$this->set('lan_days', $this->Lan->LanDay->find('all', array(
					'conditions' => array(
						'LanDay.lan_id' => $lan_id
					),
					'order' => array(
						'LanDay.date ASC',
					)
						)
				)
		);

		$this->set('lan_invites', $this->Lan->LanInvite->find('all', array(
					'conditions' => array(
						'LanInvite.lan_id' => $lan_id
					)
						)
				)
		);

		$this->set('count_lan_signups', $this->Lan->LanSignup->countTotalInLan($lan_id));
		$this->set('count_lan_signups_guests', $this->Lan->LanInvite->countGuestsInLan($lan_id));

		// Tournaments signed up for LAN
		$conditions_tournaments = array(
			'Tournament.lan_id' => $lan_id,
		);

		$this->Lan->Tournament->recursive = 2;
		$tournaments = $this->Lan->Tournament->find('all', array(
			'conditions' => $conditions_tournaments
				));

		$this->set(compact('tournaments'));

		// Users signed up for LAN
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
				)
		);

		// Crew signed up for LAN
		$lan_signups_crew = $this->Lan->LanSignup->find('all', array(
			'conditions' => array(
				'LanSignup.lan_id' => $lan_id,
			)
				)
		);

		$lan_signups_id_crew = array();

		foreach($lan_signups_crew as $lan_signup_crew){
			$lan_signups_id_crew[] = $lan_signup_crew['LanSignup']['id'];
		}

		$this->set(compact('lan_signups_crew'));

		$this->paginate = array(
			'LanSignup' => array(
				'conditions' => array(
					'LanSignup.lan_id' => $lan_id,
					'NOT' => array(
						'LanSignup.id' => $lan_signups_id_crew
					)
				),
				'limit' => 10,
				'order' => array(
					array('User.name' => 'asc')
				)
			),
		);

		$lan_signups = $this->paginate('LanSignup');

//		debug($lan_signups);

		$this->set(compact('lan_signups'));

		// Pizza waves
		$this->Lan->PizzaWave->recursive = 2;
		$this->Lan->PizzaWave->unbindModel(array(
			'belongsTo' => array(
				'Lan'
			),
			'hasOne' => array(
			),
			'hasMany' => array(
			)
				)
		);

		$this->Lan->PizzaWave->PizzaOrder->unbindModel(array(
			'belongsTo' => array(
				'User', 'PizzaWave'
			),
			'hasOne' => array(
			),
			'hasMany' => array(
			)
				)
		);

		$pizza_waves = $this->Lan->PizzaWave->find('all', array(
			'conditions' => array(
				'PizzaWave.lan_id' => $lan_id,
			)
				)
		);

		$total_pizzas = 0;
		$total_pizza_orders = 0;
		foreach ($pizza_waves as $wave) {
			$total_pizza_orders += count($wave['PizzaOrder']);
			foreach ($wave['PizzaOrder'] as $order) {
				foreach ($order['PizzaOrderItem'] as $item) {
					$total_pizzas += $item['price'] * $item['amount'];
				}
			}
		}

		$this->set(compact('total_pizzas', 'total_pizza_orders'));


		if ($lan['Lan']['sign_up_open'] && isset($user)) {

			if ($user['type'] == 'student') {
				if (!$this->Lan->isUserAttending($lan_id, $user['id'])) {
					$this->set('is_not_attending', 1);
				} else {
					if ($this->request->is('post')) {
						$this->request->data['LanInvite']['lan_id'] = $lan_id;
						$this->request->data['LanInvite']['user_student_id'] = $user['id'];
						$this->request->data['LanInvite']['time_invited'] = date('Y-m-d H:i:s');

						if ($this->Lan->LanInvite->save($this->request->data)) {
							$this->Session->setFlash('Your invite has been sent', 'default', array('class' => 'message success'), 'good');
						} else {
							$this->Session->setFlash('Unable to send your invite', 'default', array(), 'bad');
						}
					}

					$user_guests = $this->Lan->getInviteableUsers($lan_id, $user['id']);
					$this->set(compact('user_guests'));
				}
			}
		}
	}

	public function add() {
		if ($this->request->is('post')) {

			$this->request->data['Lan']['slug'] = $this->Lan->stringToSlug($this->request->data['Lan']['title']);
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

		if ($this->request->is('get')) {
			$this->request->data = $this->Lan->read();
		} else {
			$this->request->data['Lan']['slug'] = $this->Lan->stringToSlug($this->request->data['Lan']['title']);

			if ($this->Lan->save($this->request->data)) {
				$this->Session->setFlash(__('The LAN has been saved'), 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Lan could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
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
