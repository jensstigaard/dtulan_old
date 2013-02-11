<?php

App::uses('Email', 'Model');

class LansController extends AppController {

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

		$this->set('title_for_layout', $title);
		$this->set(compact('title'));

		$this->set('lan', $this->Lan->read());

		$this->set('tabs', $this->Lan->getTabs());
		$this->set('data', $this->Lan->getGeneralStatistics());
		
		$this->set('is_cancelable', $this->Lan->isLoggedIn() && $this->Lan->isUserAttendingAsGuest() && $this->Lan->isSignupOpen());

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

		$this->Lan->set('sign_up_open', 1);

		if ($this->Lan->save()) {
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

	public function view_signup_codes($slug) {
		$this->layout = 'print';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->set('lan', $this->Lan->find('first', array(
						'conditions' => $this->Lan->id,
						'fields' => array(
							 'title',
							 'slug',
						)
				  )));

		$this->set('codes', $this->Lan->LanSignupCode->find('all', array(
						'conditions' => array(
							 'LanSignupCode.lan_id' => $this->Lan->id
						)
				  )));

		$this->set('settings', array(
			 'columns' => 3,
			 'rows_per_page' => 4
		));
	}

	// Print all data about Lan (participants)
	public function view_print($slug) {
		$this->layout = 'print';

		$this->Lan->id = $this->Lan->getIdBySlug($slug);

		$this->Lan->read(array('title'));

		$this->set('title_for_layout', 'Lan &bull; ' . $this->Lan->data['Lan']['title']);

		$this->set('lan', $this->Lan->find('first', array(
						'conditions' => array(
							 'id' => $this->Lan->id
						),
						'contain' => array(
							 'LanSignup' => array(
								  'User'
							 ),
							 'Crew' => array(
								  'User'
							 )
						)
				  )));
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
