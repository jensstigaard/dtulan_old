<?php

/**
 * Description of UsersController
 *
 * @author Nigrea, Jens & Casper
 */
class UsersController extends AppController {

	public $components = array('RequestHandler');
	public $name = 'Users';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login', 'add', 'activate', 'forgot_password', 'reset_password', 'api_view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)) {
			return true;
		} elseif (in_array($this->action, array(
					'profile',
					'logout',
					'edit',
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

		$this->User->unbindModel(array('hasMany' => array('PizzaOrder', 'LanSignup')));
		$user = $this->User->read();

		if ($user['User']['id'] == $this->Auth->user('id')) {
			$is_you = true;
		}

		if ($is_you || $this->isAdmin()) {
			$is_auth = true;
		}

		$title_for_layout = 'Profile &bull; ' . $user['User']['name'];


		// Teams for user
		$this->User->TeamUser->recursive = 2;
		$this->User->TeamUser->Team->Tournament->unbindModel(array('belongsTo' => array('Lan')));

		$teams = $this->User->TeamUser->find('all', array('conditions' => array(
				'TeamUser.user_id' => $id
			)
				)
		);

		// Pizza orders
		$this->User->PizzaOrder->recursive = 3;
		$this->User->PizzaOrder->unbindModel(array('belongsTo' => array('User')));
		$this->User->PizzaOrder->PizzaWave->unbindModel(array('hasMany' => array('PizzaOrder'), 'belongsTo' => array('Lan')));
		$this->User->PizzaOrder->PizzaOrderItem->unbindModel(array('belongsTo' => array('PizzaOrder')));
//		$this->User->PizzaOrder->PizzaWave->Lan->unbindModel(array('hasMany' => array('LanSignup', 'Tournament', 'LanDay', 'PizzaWave')));
		$pizza_orders = $this->User->PizzaOrder->find('all', array('conditions' => array(
				'PizzaOrder.user_id' => $id
			)
				)
		);

		$pizza_order_cancelable = array();
		foreach ($pizza_orders as $pizza_order) {
			$pizza_orders_cancelable[$pizza_order['PizzaOrder']['id']] = $this->User->PizzaOrder->isCancelable($pizza_order['PizzaOrder']['id'], $this->isAdmin());
		}


		// Lan signups
		$this->User->LanSignup->recursive = 2;
		$this->User->LanSignup->unbindModel(array('belongsTo' => array('User')));
		$this->User->LanSignup->Lan->unbindModel(array('hasMany' => array('LanSignup', 'Tournament', 'PizzaWave')));
		$this->User->LanSignup->LanSignupDay->unbindModel(array('belongsTo' => array('LanSignup')));

		$lans = $this->User->LanSignup->find('all', array('conditions' => array(
				'LanSignup.user_id' => $id
			),
				)
		);

		if ($user['User']['type'] == 'student') {
			// Lan invites (accepted) made by user
			$lan_invites_accepted = array();
			$this->User->LanInvite->recursive = 1;
			foreach ($lans as $lan) {
				$lan_invites_accepted[$lan['Lan']['id']] = $this->User->LanInvite->find('all', array(
					'conditions' => array(
						'LanInvite.user_student_id' => $id,
						'LanInvite.accepted' => 1,
						'LanInvite.lan_id' => $lan['Lan']['id']
					)
						)
				);
			}

			$this->set(compact('lan_invites_accepted'));
		}




		$this->set(compact(
						'title_for_layout', 'is_you', 'is_auth', 'user', 'pizza_orders', 'pizza_orders_cancelable', 'lans', 'teams'
				)
		);
	}

	public function add() {

		App::uses('CakeEmail', 'Network/Email');

		$this->set('title_for_layout', 'Register new user');

		if ($this->request->is('post')) {

			$this->request->data['User']['time_created'] = date('Y-m-d H:i:s');

			$email = $this->request->data['User']['email_gravatar'] = $this->request->data['User']['email'];

			$name = $this->request->data['User']['name'];

			$this->User->create();

			if ($this->User->save($this->request->data)) {

				$id = $this->User->getLastInsertID();


				ini_set("SMTP", 'smtp.unoeuro.com');

				$email = new CakeEmail();
				$email->config('smtp');
				$email->emailFormat('html');
				$email->template('user_activate');
				$email->from(array('no-reply@dtu-lan.dk' => 'DTU LAN site - No reply'));
				$email->to($this->request->data['User']['email']);
				$email->subject('DTU-LAN site - Activation');
				$email->viewVars(array('title_for_layout' => 'Activate user', 'activate_id' => $id, 'name' => $name));
				$email->send();


				$this->Session->setFlash(__('Your user has been registered. An email is sent to ' . $email . '. Follow the instructions in the email to continue the activation process. Remember to check your spam folder'), 'default', array('class' => 'message success'), 'good');
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please try again.'), 'default', array(), 'bad');
			}
		}
	}

	public function edit() {
		$this->set('title_for_layout', 'Edit personal data');

		$this->User->id = $this->Auth->user('id');

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->User->read();
		} else {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('User has been saved'), 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'profile'));
			} else {
				$this->Session->setFlash(__('User could not be saved. Please try again'), 'default', array(), 'bad');
				debug($this->User->validates());
			}
		}

		$this->set('this_user', $this->User->read());
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
		App::uses('CakeEmail', 'Network/Email');

		$this->set('title_for_layout', 'Forgot password');

		if ($this->request->is('post')) {
			$email = $this->request->data['User']['email'];

			$user = $this->User->find('first', array(
				'conditions' => array(
					'User.email' => $email
				),
				'recursive' => 0
					)
			);

			if (!$user) {
				$this->Session->setFlash(__('No user with entered email'), 'default', array(), 'bad');
			} else {
				if (isset($user['UserPasswordTicket']['time'])) {
					$this->User->UserPasswordTicket->id = $user['UserPasswordTicket']['id'];
					if ($this->User->UserPasswordTicket->saveField('time', date('Y-m-d H:i:s'), true)) {
						$saved = 1;
						$id = $user['UserPasswordTicket']['id'];
					}
				} else {
					$this->request->data['UserPasswordTicket']['user_id'] = $user['User']['id'];
					$this->request->data['UserPasswordTicket']['time'] = date('Y-m-d H:i:s');

					if ($this->User->UserPasswordTicket->save($this->request->data)) {
						$saved = 1;
						$id = $this->User->UserPasswordTicket->getLastInsertID();
					}
				}

				if (!isset($saved)) {
					$this->Session->setFlash(__('Fatal error during database call. Please try again'), 'default', array(), 'bad');
				} else {

					ini_set("SMTP", 'smtp.unoeuro.com');

					$email = new CakeEmail();
					$email->config('smtp');
					$email->emailFormat('html');
					$email->template('user_forgot_password');
					$email->from(array('no-reply@dtu-lan.dk' => 'DTU LAN Party'));
					$email->to($user['User']['email']);
					$email->subject('DTU LAN site - Password reset');
					$email->viewVars(array('title_for_layout' => 'Forgot password', 'name' => $user['User']['name'], 'ticket_id' => $id));

					if ($email->send()) {
						$this->Session->setFlash(__('Email sent'), 'default', array('class' => 'message success'), 'good');
					} else {
						$this->Session->setFlash('Fatal error during sending. Please try again.', 'default', array(), 'bad');
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

	public function api_view($id) {
		if ($this->request->is('get')) {
//			if ($this->isJsonRequest()) {
				$this->User->id = $id;
				if ($this->User->exists()) {
					$this->User->read();
                                        $this->User->data['User']['email_gravatar'] = $this->User->data['User']['email_gravatar'] 
					$this->set('success', true);
					$this->set('data', array('user' => $this->User->data));
					$this->set('_serialize', array('success', 'data'));
				} else {
					throw new InvalidArgumentException(__('Invalid user'));
				}
//			} else {
//				throw new BadRequestException;
//			}
		} else {
			throw new MethodNotAllowedException;
		}
	}

}
