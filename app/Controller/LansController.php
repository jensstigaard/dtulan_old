<?php

App::uses('Email', 'Model');

class LansController extends AppController {

	public $helpers = array('Html', 'Form', 'Js');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(
				  'view', 'view_general', 'view_participants', 'view_crew', 'view_tournaments'
		);
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Lan->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->set('lans', $this->Lan->getIndexList());
	}

	public function view($slug) {

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->Lan->read(array('title'));

		$title = $this->Lan->data['Lan']['title'];

		$title_for_layout = 'Lan &bull; ' . $title;

		$this->set(compact('title', 'title_for_layout'));

		$this->set('lan', $this->Lan->read(array(
						'id',
						'slug',
						'price',
						'time_start',
						'time_end',
						'published',
						'sign_up_open',
						'max_participants'
				  )));

		$this->set('tabs', $this->Lan->getTabs());
		$this->set('data', $this->Lan->getGeneralStatistics());

		if ($this->Lan->isYouAdmin()) {
			$this->set('tabs_admin', $this->Lan->getTabsAdmin());
		}
	}

	/* -- Crew tab -- */

	public function view_crew($slug) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->set('lan_slug', $slug);

		$this->set('crew', $this->Lan->getCrewData());
	}

	/* - Participants tab -- */

	public function view_participants($slug) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->paginate = array(
			 'LanSignup' => array(
				  'conditions' => array(
						'LanSignup.lan_id' => $this->Lan->id,
				  ),
				  'recursive' => 1,
				  'limit' => 15,
				  'order' => array(
						array('User.name' => 'asc')
				  )
			 ),
		);

		$participants = $this->paginate('LanSignup');

		$this->set(compact('participants'));
	}

	/* -- Tournaments tab -- */

	public function view_tournaments($slug) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->paginate = array(
			 'Tournament' => array(
				  'conditions' => array(
						'Tournament.lan_id' => $this->Lan->id,
				  ),
				  'recursive' => -1,
				  'fields' => array(
						'id',
						'slug',
						'time_start',
						'game_id',
						'team_size'
				  ),
				  'limit' => 10,
				  'order' => array(
						array('time_start' => 'asc')
				  )
			 ),
		);

		$this->set('tournaments', $this->Lan->getDataForTournaments($this->paginate('Tournament')));


		$this->set('lan', $this->Lan->read(array(
						'id',
						'slug'
				  )));
	}

	/* -- Pizza menus tab -- */

	public function view_pizzamenus($slug) {

		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);
		$this->set('id', $this->Lan->id);

		$this->set('pizza_menus', $this->Lan->getPizzaMenus());
	}

	/* -- Food menus tab -- */

	public function view_foodmenus($slug) {

		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);
		$this->set('id', $this->Lan->id);

		$this->set('food_menus', $this->Lan->getFoodMenus());
	}

	/* -- Economics tab -- */

	public function view_economics($slug) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->set('data', $this->Lan->getEconomics());
	}

	/* -- Lan invites tab -- */

	public function view_invites($slug) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->set('lan_invites', $this->Lan->getInvites());
	}

	public function add() {
		if ($this->request->is('post')) {

			if (isset($this->request->data['Lan']['need_physical_code']) && $this->request->data['Lan']['need_physical_code']) {
				$this->request->data['LanSignupCode'] = $this->Lan->generateLanSignupCodes($this->request->data['Lan']['max_participants']);
			}

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

	public function openForSignup($id) {

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

	public function sendEmailSubscribers($id) {

		$this->Lan->getEventManager()->attach(new Email());

		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException(__('Invalid Lan'));
		}

		$this->Lan->recursive = -1;
		$this->Lan->read(array('id', 'title', 'time_start'));

		$this->request->data['Lan'] = $this->Lan->data;

		if ($this->request->is('post')) {
			if ($this->Lan->sendSubscriptionEmails($this->request->data)) {
				$this->Session->setFlash(__('Emails has been sent!'), 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash(__('Emails could not be sent. Please try again'), 'default', array(), 'bad');
			}
		}

		$this->set('title', $this->Lan->data['Lan']['title']);
	}

	// Print all data about Lan (participants)
	public function view_print($slug) {
		$this->layout = 'print';
		$cond = array('Lan.slug' => $slug);

		$lan = $this->Lan->find('first', array('conditions' => $cond));


		if (!$lan) {
			throw new NotFoundException('No LAN found');
		}

		$lan_id = $lan['Lan']['id'];

		$this->Lan->id = $lan_id;

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

		$this->set('count_lan_signups', $this->Lan->countSignups());
		$this->set('count_lan_signups_guests', $this->Lan->countGuests());

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

		$lan_crew_ids = $this->Lan->getCrewMembersUserId();


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
