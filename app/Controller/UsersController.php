<?php

/**
 * Description of UsersController
 *
 * @author Nigrea, Jens & Casper
 */
App::uses('Email', 'Model');

class UsersController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(
				  'login', 'add', 'activate', 'forgot_password', 'reset_password', 'api_view'
		);
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->User->isYouAdmin()) {
			return true;
		} elseif (in_array($this->action, array(
						'profile',
						'logout',
						'edit',
						'view_pizzaorders',
						'view_foodorders',
						'view_payments',
						'view_tournaments',
						'view_lans'
				  ))) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->User->recursive = 0;

		$this->paginate = array(
			 'User' => array(
				  'limit' => 10,
				  'order' => 'time_created ASC'
			 )
		);

		$this->set('users', $this->paginate('User'));
	}

	public function profile($id = null) {

		$is_you = false;
		$is_auth = false;

		if ($id == null) {
			$id = $this->Auth->user('id');
		}

		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$this->User->unbindModel(array('hasMany' => array('PizzaOrder', 'FoodOrder', 'LanSignup')));
		$user = $this->User->read();

		if ($user['User']['id'] == $this->Auth->user('id')) {
			$is_you = true;
		}

		if ($is_you || $this->User->isYouAdmin()) {
			$is_auth = true;
		}

		$title_for_layout = 'Profile &bull; ' . $user['User']['name'];

		if ($this->User->isCrewForUser($this->Auth->user('id'))) {
			$crew_info = $this->User->getNewestCrewId($this->Auth->user('id'));
			$this->set('make_payment_crew_id', $crew_info['CrewId']);
			$this->set('make_payment_lan_title', $crew_info['LanTitle']);
		}

		$this->set(compact(
							 'title_for_layout', 'is_you', 'is_auth', 'user'
				  )
		);
	}

	public function view_tournaments($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException('User not found with ID #' . $id);
		}

		$this->set('teams', $this->User->getTeams());
	}

	public function view_lans($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException('User not found with ID #' . $id);
		}

		$this->User->read(array('type'));


		// Lan signups
		$this->User->LanSignup->unbindModel(array('belongsTo' => array('User')));
		$this->User->LanSignup->Lan->unbindModel(array('hasMany' => array('LanSignup', 'Tournament', 'PizzaWave')));

		$lans = $this->User->LanSignup->find('all', array(
			 'conditions' => array(
				  'LanSignup.user_id' => $id
			 ),
			 'order' => array(
				  'Lan.time_start' => 'desc'
			 ),
			 'recursive' => 2
				  )
		);

		$is_you = false;
		if ($id == $this->Auth->user('id')) {
			$is_you = true;
		}

		$is_auth = false;
		if ($is_you || $this->User->isYouAdmin()) {
			$is_auth = true;
		}

		$this->set(compact('lans', 'is_you', 'is_auth'));
		$this->set('user_type', $this->User->data['User']['type']);
	}

	public function view_payments($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException('User not found with ID #' . $id);
		}
		if ($id !== $this->Auth->user('id') && !$this->User->isYouAdmin()) {
			throw new UnauthorizedException;
		}

		$this->paginate = array(
			 'Payment' => array(
				  'conditions' => array(
						'Payment.user_id' => $id,
				  ),
				  'recursive' => 0,
				  'limit' => 10,
				  'order' => array(
						array('Payment.time' => 'desc')
				  )
			 ),
		);

		$payments = $this->paginate('Payment');

		$this->User->dateToNiceArray($payments, 'Payment');

		$this->set(compact('payments'));
	}

	public function view_pizzaorders($id) {

		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException('User not found with ID #' . $id);
		}
		if ($id !== $this->Auth->user('id') && !$this->User->isYouAdmin()) {
			throw new UnauthorizedException;
		}

		// Pizza orders
		$this->User->PizzaOrder->unbindModel(array('belongsTo' => array('User')));
		$this->User->PizzaOrder->PizzaWave->unbindModel(array('hasMany' => array('PizzaOrder'), 'belongsTo' => array('Lan')));
		$this->User->PizzaOrder->PizzaOrderItem->unbindModel(array('belongsTo' => array('PizzaOrder')));
//		$this->User->PizzaOrder->PizzaWave->Lan->unbindModel(array('hasMany' => array('LanSignup', 'Tournament', 'LanDay', 'PizzaWave')));
		$pizza_orders = $this->User->PizzaOrder->find('all', array(
			 'conditions' => array(
				  'PizzaOrder.user_id' => $id
			 ),
			 'recursive' => 3,
			 'limit' => 10
				  )
		);

		foreach ($pizza_orders as $pizza_order_nr => $pizza_order) {
			$this->User->PizzaOrder->id = $pizza_order['PizzaOrder']['id'];
			$pizza_orders[$pizza_order_nr]['PizzaOrder']['is_cancelable'] = $this->User->PizzaOrder->isCancelable();
		}

		$this->User->PizzaOrder->dateToNiceArray($pizza_orders, 'PizzaOrder');


		$is_you = false;
		if ($id == $this->Auth->user('id')) {
			$is_you = true;
		}

		$this->set(compact('pizza_orders', 'is_you'));
	}

	public function view_foodorders($id) {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException('User not found with ID #' . $id);
		}
		if ($id !== $this->Auth->user('id') && !$this->isAdmin()) {
			throw new UnauthorizedException;
		}

		$this->paginate = array(
			 'FoodOrder' => array(
				  'conditions' => array(
						'FoodOrder.user_id' => $id,
				  ),
				  'recursive' => 3,
				  'limit' => 10,
				  'order' => array(
						array('FoodOrder.time' => 'desc')
				  )
			 ),
		);

		$food_orders = $this->paginate('FoodOrder');

		$this->User->FoodOrder->dateToNiceArray($food_orders, 'FoodOrder');

		$is_you = false;
		if ($id == $this->Auth->user('id')) {
			$is_you = true;
		}

		$this->set(compact('food_orders', 'is_you'));
	}

	public function add() {

		$this->set('title_for_layout', 'Register new user');

		if ($this->request->is('post')) {

			$this->User->getEventManager()->attach(new Email());

			$data = array(
				 'email' => $this->request->data['User']['email'],
				 'name' => $this->request->data['User']['name']
			);

			if ($this->User->createUser($data)) {
				$id = $this->User->getLastInsertID();
				$this->Session->setFlash(__('Your user has been registered. An email is sent to ' . $data['email'] . '. Follow the instructions in the email to continue the activation process. Remember to check your spam folder'), 'default', array('class' => 'message success'), 'good');
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please try again.'), 'default', array(), 'bad');
			}
		}
	}

	public function edit() {
		$this->set('title_for_layout', 'Edit personal data');

		$this->User->id = $this->User->getLoggedInId();

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->User->getDataToEditPage();
		} else {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('User has been saved'), 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'profile'));
			} else {
				$this->Session->setFlash(__('User could not be saved. Please try again'), 'default', array(), 'bad');
			}
		}

		$this->set('email_gravatar', $this->request->data['User']['email_gravatar']);
	}

	public function activate($id = null) {

		$user = $this->User->read(array('id', 'activated', 'email', 'name'), $id);

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->User->isActivated()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$this->set('title_for_layout', 'Activate user');

		if ($this->request->is('post')) {

			// Setting fields in $user for saving
			$this->request->data['User']['activated'] = 1;
			$this->request->data['User']['time_activated'] = date('Y-m-d H:i:s');

			$password = $this->request->data['User']['password'];

			if ($this->User->save($this->request->data)) {
				/*
				 * Logs user in after successful activation
				 */

				unset($this->request->data['User']);
				$this->request->data['User']['email'] = $user['User']['email'];
				$this->request->data['User']['password'] = $password;

				if ($this->Auth->login()) {
					$this->Session->setFlash(__('Your account is activated. Welcome ' . $data['User']['name']), 'default', array('class' => 'message success'), 'good');
					$this->redirect('/');
				} else {
					$this->Session->setFlash(__('Your account is activated. Please log in.'), 'default', array('class' => 'message success'), 'good');
					$this->redirect(array('action' => 'login'));
				}
			} else {
				$this->Session->setFlash(__('Account was not activated'), 'default', array(), 'bad');
			}
		}


		/**
		 * @author Casper
		 * Ask me if you have any questions. This is only an alternative.

		  $this->User->read(array('id', 'activated', 'email', 'name'), $id);
		  if (!$this->User->exists()) {
		  throw new NotFoundException(__('Invalid user'));
		  }
		  if ($this->User->isActivated()) {
		  throw new NotFoundException(__('Invalid user'));
		  }
		  $this->set('title_for_layout', 'Activate user');

		  if ($this->request->is('post')) {

		  $this->User->set('activated', 1);
		  $this->User->set('time_activated', date('Y-m-d H:i:s'));
		  $this->User->set('password', $this->request->data['User']['password']);
		  $this->User->set('password_confirmation', $this->request->data['User']['password_confirmation']);

		  if ($this->User->save()) {
		  // Logs user in after successful activation
		  unset($this->request->data['User']);
		  $this->request->data['User']['email'] = $this->User->getEmail();
		  $this->request->data['User']['password'] = $this->User->data['User']['password'];
		  if ($this->Auth->login()) {
		  $this->Session->setFlash(__('Your account is activated. Welcome ' . $this->User->getName()), 'default', array('class' => 'message success'), 'good');
		  $this->redirect('/');
		  } else {
		  $this->Session->setFlash(__('Your account is activated. Please log in.'), 'default', array('class' => 'message success'), 'good');
		  $this->redirect(array('action' => 'login'));
		  }
		  } else {
		  $this->Session->setFlash(__('Account was not activated'), 'default', array(), 'bad');
		  }
		  }
		 */
	}

	public function login() {
		$this->set('title_for_layout', 'User login');
//		if ($this->request->is('post') && $this->request->accepts("application/vnd.dtulan+json; version=1.0")) {
//			$this->response->header(array('content-type: application/json'));
//			$this->render('API/response');
//			$data = array();
//			if ($this->Auth->login()) {
//				$this->set('succes', 'true');
//				$this->set($data, array('access_token' => $this->Auth->user('access_token')));
//			} else {
//				$this->set('succes', 'false');
//				$this->set($data, array('message' => _('Invalid email or password!')));
//			}
//		} else
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->Session->setFlash(__('You are now logged in'), 'default', array('class' => 'message success'), 'good');

				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Your username/password combination was incorrect'), 'default', array(), 'bad');
			}
		}
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function forgot_password() {
		$this->set('title_for_layout', 'Forgot password');

		if ($this->request->is('post')) {

			$this->User->getEventManager()->attach(new Email());

			$user = $this->User->find('first', array(
				 'conditions' => array(
					  'email' => $this->request->data['User']['email']
				 ),
				 'fields' => array(
					  'id'
				 )
					  ));

			if (!isset($user['User']['id'])) {
				$this->Session->setFlash(__('No user with this email.'), 'default', array(), 'bad');
			} else {
				$this->User->id = $user['User']['id'];

				if (!$this->User->exists()) {
					$this->Session->setFlash(__('No user with this email. '), 'default', array(), 'bad');
				} else {
					if ($this->User->createForgotPassword()) {
						$this->Session->setFlash(__('Email sent!'), 'default', array('class' => 'message success'), 'good');
					} else {
						$this->Session->setFlash(__('Fatal error'), 'default', array(), 'bad');
					}
				}
			}
		}
	}

	public function reset_password($id = null) {
		$this->set('title_for_layout', 'Reset password');

		$this->User->UserPasswordTicket->id = $id;

		if (!$this->User->UserPasswordTicket->exists()) {
			throw new NotFoundException('Key not found');
		}

		$ticket = $this->User->UserPasswordTicket->read();

		if ($ticket['UserPasswordTicket']['time'] > strtotime('+ 1day')) {
			throw new NotFoundException('Invalid link');
		}

		if ($this->request->is('post')) {

			$this->request->data['User']['id'] = $ticket['UserPasswordTicket']['user_id'];

			if ($this->User->save($this->request->data) && $this->User->UserPasswordTicket->delete()) {
				$this->Session->setFlash(__('Password has been updated'), 'default', array('class' => 'message success'), 'good');
				$this->redirect('/');
			} else {
				$this->Session->setFlash('Unable to save password. Please try again. ', 'default', array(), 'bad');
			}
		}
	}

	public function lookup() {

		$users = array();

		if ($this->request->is('post')) {

			$input_string = $this->request->data['search_startsWith'];

			$users = $this->User->find('all', array(
				 'recursive' => -1,
				 'conditions' => array(
					  'OR' => array(
							array(
								 'name LIKE' => '%' . $input_string . '%'
							),
							array(
								 'email LIKE' => '%' . $input_string . '%'
							),
							array(
								 'id_number LIKE' => '%' . $input_string . '%'
							),
					  )
				 ),
				 'limit' => 5
					  )
			);
		}

		$this->set('users', $users);
		$this->set('_serialize', array('users'));
	}

	public function api_view($id = '') {
		if ($this->request->is('get')) {
			$this->User->id = $id;
			if ($this->User->exists()) {
				$this->User->recursive = -1;
				$this->User->read();
				$this->User->data['User']['image_url'] = 'http://www.gravatar.com/avatar/' . md5($this->User->data['User']['email_gravatar']) . '?s=100&r=r';
				$data = array();
				$data['user'] = $this->User->data;
				if (isset($this->params['named']['pizza_orders']) && $this->params['named']['pizza_orders'] === 'true') {
					$data['pizza_orders'] = $this->User->PizzaOrder->getPizzaOrdersByUser($this->User->id);
				}
				$this->set('success', true);
				$this->set('data', $data);
				$this->set('_serialize', array('success', 'data'));
			} else {
				throw new BadRequestException;
			}
		} else {
			throw new MethodNotAllowedException;
		}
	}

}
