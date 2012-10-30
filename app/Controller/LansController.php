<?php

class LansController extends AppController {

	public $components = array('RequestHandler');
	public $helpers = array('Html', 'Form', 'Js');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('view', 'view_participants', 'view_crew', 'view_tournaments');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Lan->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->Lan->recursive = 1;
		$this->set('lans', $this->Lan->find('all'));
	}

	public function view($slug) {

		$cond = array('Lan.slug' => $slug);

		if ($this->Auth->loggedIn()) {

			if (!$this->Lan->isYouAdmin()) {
				$cond['published'] = 1;
			}
		} else {
			$cond['published'] = 1;
		}

		$lan = $this->Lan->find('first', array('conditions' => $cond));

		if (!$lan) {
			throw new NotFoundException('No LAN found');
		}

		$this->Lan->id = $lan['Lan']['id'];
		if (!$this->Lan->exists()) {
			throw new NotFoundException('LAN not found with id ' . $this->Lan->id);
		}

		$title_for_layout = 'Lan &bull; ' . $lan['Lan']['title'];

		$this->set(compact('lan', 'title_for_layout'));

		if($this->Auth->loggedIn()){
			$this->Lan->LanSignup->User->id = $this->Auth->user('id');

			$this->set('signup_available', $this->Lan->isUserAbleSignup());
		}


	}

	public function view_general($id) {

		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		$this->set('lan', $this->Lan->read(array('price', 'time_start', 'time_end', 'published', 'sign_up_open')));

		$this->set('count_tournaments', $this->Lan->countTournaments());
		$this->set('count_lan_signups', $this->Lan->countSignups());
		$this->set('count_lan_signups_guests', $this->Lan->countGuests());
		$this->set('count_invites', $this->Lan->countInvites());
		$this->set('lan_days', $this->Lan->getLanDays());
	}

	/* -- Crew tab -- */

	public function view_crew($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		$this->set('crew', $this->Lan->LanSignup->getLanSignupsCrew($id));
	}

	/* - Participants tab -- */

	public function view_participants($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		$this->paginate = array(
			'LanSignup' => array(
				'conditions' => array(
					'LanSignup.lan_id' => $id,
					'NOT' => array(
						'LanSignup.id' => $this->Lan->LanSignup->getLanSignupsCrewIds($id)
					)
				),
				'recursive' => 2,
				'limit' => 10,
				'order' => array(
					array('User.name' => 'asc')
				)
			),
		);

		$participants = $this->paginate('LanSignup');

		$this->set(compact('participants'));
	}

	/* -- Tournaments tab -- */

	public function view_tournaments($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		$this->paginate = array(
			'Tournament' => array(
				'conditions' => array(
					'Tournament.lan_id' => $id,
				),
				'recursive' => 2,
				'limit' => 10,
				'order' => array(
					array('Tournament.time_start' => 'asc')
				)
			),
		);

		$this->set('tournaments', $this->paginate('Tournament'));

		$this->set('lan_id', $id);
	}

	/* -- Pizza waves tab -- */

	public function view_pizzawaves($id) {

		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $id;

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		$pizza_waves = $this->Lan->PizzaWave->find('all', array(
			'conditions' => array(
				'PizzaWave.lan_id' => $id
			)
				)
		);

		$this->Lan->PizzaWave->dateToNiceArray($pizza_waves, 'PizzaWave', 'time_start', false);

		foreach ($pizza_waves as $pizza_wave_nr => $pizza_wave_content) {
			$pizza_waves[$pizza_wave_nr]['PizzaWave']['pizza_order_total'] = $this->Lan->PizzaWave->getOrdersSum($pizza_wave_content['PizzaWave']['id']);
		}


		$this->set(compact('pizza_waves'));
		$this->set('lan_id', $id);
	}

	/* -- Economics tab -- */

	public function view_economics($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $id;

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		$this->set('lan', $this->Lan->read());

		$this->set('count_lan_signups', $this->Lan->countSignups());
		$this->set('count_lan_signups_guests', $this->Lan->countGuests());
		$this->set('total_pizzas', $this->Lan->PizzaWave->getTotalPizzasByLan($this->Lan->id));
		$this->set('total_pizza_orders', $this->Lan->PizzaWave->getTotalPizzaOrdersByLan($this->Lan->id));
		$this->set('food_orders_count', $this->Lan->getCountFoodOrders());
		$this->set('food_orders_total', $this->Lan->getFoodOrdersTotal());
	}

	public function view_invites($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $id;

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		$this->set('lan_invites', $this->Lan->getInvites());
	}

	public function add() {
		if ($this->request->is('post')) {

			$this->request->data['Lan']['slug'] = $this->Lan->stringToSlug($this->request->data['Lan']['title']);
			$this->request->data['LanDay'] = $this->Lan->getLanDaysByTime($this->request->data['Lan']['time_start'], $this->request->data['Lan']['time_end']);
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

		$this->set(compact('id'));
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

	public function view_print($slug) {
		$this->layout = 'print';
		$cond = array('Lan.slug' => $slug);

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

		$this->Lan->LanInvite->recursive = 2;

		$this->set('lan_invites', $this->Lan->LanInvite->find('all', array(
					'conditions' => array(
						'LanInvite.lan_id' => $lan_id,
						'LanInvite.accepted' => 0
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

		$this->Lan->Crew->recursive = 0;
		$lan_crews = $this->Lan->getCrew();

		$lan_crew_ids = array();
		foreach ($lan_crews as $crew) {
			$lan_crew_ids[] = $crew['Crew']['user_id'];
		}

		// Crew signed up for LAN
		$lan_signups_crew = $this->Lan->LanSignup->find('all', array(
			'conditions' => array(
				'LanSignup.lan_id' => $lan_id,
				'LanSignup.user_id' => $lan_crew_ids,
			),
			'order' => array(
				'User.name'
			)
				)
		);

		$lan_signups_id_crew = array();

		foreach ($lan_signups_crew as $lan_signup_crew) {
			$lan_signups_id_crew[] = $lan_signup_crew['LanSignup']['id'];
		}


		$lan_signups = $this->Lan->LanSignup->find('all', array(
			'conditions' => array(
				'LanSignup.lan_id' => $lan_id,
				'NOT' => array(
					'LanSignup.id' => $lan_signups_id_crew
				)
			),
			'order' => array(
				array('User.name' => 'asc')
			)
				)
		);

		$this->set(compact('lan_signups', 'lan_signups_crew'));
	}

	public function delete($id) {
		$this->Lan->id = $id;

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found with id #' . $id);
		}

		if ($this->Lan->delete()) {
			$this->Session->setFlash(__('The LAN has been deleted'), 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash(__('The Lan could not be deleted'), 'default', array(), 'bad');
		}
		$this->redirect($this->referer());
	}

}
