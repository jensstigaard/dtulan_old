<?php

/**
 * Description of UsersController
 *
 * @author Nigrea, Jens & Casper
 */
class UsersController extends AppController {

	public $name = 'Users';
	// Pass settings in $components array
	public $components = array(
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email'),
					'scope' => array(
						'activated' => 1
					)
				)
			),
		)
	);

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('login', 'add', 'activate', 'forgot_password', 'reset_password');
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
		$this->set('users', $this->paginate());
	}

	public function profile($id = null) {
		if ($id == null) {
			$user = $this->Auth->user();
			$id = $user['id'];
		}

		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$this->User->unbindModel(array('hasMany' => array('PizzaOrder', 'LanSignup')));
		$user = $this->User->read();


		$this->User->TeamUser->recursive = 2;
		$this->User->TeamUser->Team->Tournament->unbindModel(array('belongsTo' => array('Lan')));

		$teams = $this->User->TeamUser->find('all', array('conditions' => array(
				'TeamUser.user_id' => $id
			)
				)
		);

		$this->User->PizzaOrder->recursive = 3;
		$this->User->PizzaOrder->unbindModel(array('belongsTo' => array('User')));
		$this->User->PizzaOrder->PizzaWave->unbindModel(array('hasMany' => array('PizzaOrder')));
		$this->User->PizzaOrder->PizzaWave->Lan->unbindModel(array('hasMany' => array('LanSignup', 'Tournament', 'LanDay', 'PizzaWave')));
		$pizza_orders = $this->User->PizzaOrder->find('all', array('conditions' => array(
				'PizzaOrder.user_id' => $id
			)
				)
		);

		$this->User->LanSignup->recursive = 2;
		$this->User->LanSignup->unbindModel(array('belongsTo' => array('User')));
		$this->User->LanSignup->Lan->unbindModel(array('hasMany' => array('LanSignup', 'Tournament', 'PizzaWave')));
		$this->User->LanSignup->LanSignupDay->unbindModel(array('belongsTo' => array('LanSignup')));

		$lans = $this->User->LanSignup->find('all', array('conditions' => array(
				'LanSignup.user_id' => $id
			)
				)
		);

		$lan_ids = array();
		foreach ($lans as $lan) {
			$lan_ids[] = $lan['Lan']['id'];
		}

		if ($user['User']['type'] == 'student') {
			$this->set('next_lan', $this->User->LanSignup->Lan->find('first', array(
						'conditions' => array(
							'Lan.sign_up_open' => 1,
							'Lan.published' => 1,
							'Lan.time_end >' => date('Y-m-d H:i:s'),
							'NOT' => array(
								'Lan.id' => $lan_ids
							)
						),
						'order' => array('Lan.time_start ASC'),
						'recursive' => 0
							)
					)
			);
		}

		$title_for_layout = 'Profile &bull; ' . $user['User']['name'];

		$this->set(compact('user', 'pizza_orders', 'lans', 'teams', 'title_for_layout'));
	}

	public function add() {

		App::uses('CakeEmail', 'Network/Email');

		$this->set('title_for_layout', 'Register new user');

		if ($this->request->is('post')) {

			$this->request->data['User']['time_created'] = date('Y-m-d H:i:s');

			$this->request->data['User']['email_gravatar'] = $this->request->data['User']['email'];

			$name = $this->request->data['User']['name'];

			$this->User->create();

			if ($this->User->save($this->request->data)) {

				$id = $this->User->getLastInsertID();

				$msg = '';
				$msg.='<h2>Hey ' . $name . '</h2>';
				$msg.='This is an autogenerated email from the DTU LAN site.';
				$msg.='<br />';
				$msg.='To finish the activation proccess, you have to select a personal password for Your account by using the activation-link below.';
				$msg.='<br />';
				$msg.='<a href="http://dtu-lan.dk/users/activate/' . $id . '">http://dtu-lan.dk/users/activate/' . $id . '</a>';
				$msg.='<br /><br />';
				$msg.='Best regards';
				$msg.='<br />';
				$msg.='The DTU LAN crew';

				ini_set("SMTP", 'smtp.unoeuro.com');

				$email = new CakeEmail();
				$email->config('smtp');
				$email->emailFormat('html');
				$email->template('default');
				$email->from(array('no-reply@dtu-lan.dk' => 'DTU LAN site - No reply'));
				$email->to($this->request->data['User']['email']);
				$email->subject('DTU-LAN site - Activation');
				$email->send($msg);


				$this->Session->setFlash(__('Your user has been registered. Check your email to continue the activation process'), 'default', array('class' => 'message success'), 'good');
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
		$this->User->data['User']['activated'];

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
					$this->Session->setFlash(__('Your account is activated. Welcome ' . $user['User']['name']), 'default', array('class' => 'message success'), 'good');
					$this->redirect('/');
				} else {
					$this->Session->setFlash(__('Your account is activated. Please log in.'), 'default', array('class' => 'message success'), 'good');
					$this->redirect(array('action' => 'login'));
				}
			} else {
				$this->Session->setFlash(__('Account was not activated'), 'default', array(), 'bad');
			}
		}
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
				$this->Session->setFlash(__('Your username/password combination was incorrect'), 'default', array('class' => 'message success'), 'bad');
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
			$user = $this->User->findByEmail($email);

			if ($user) {
				$this->request->data['UserPasswordTicket']['user_id'] = $user['User']['id'];
				$this->request->data['UserPasswordTicket']['time'] = date('Y-m-d H:i:s');


				if (!$this->User->UserPasswordTicket->save($this->request->data)) {
					$this->Session->setFlash(__('Fatal error during database call. Please try again'), 'default', array(), 'bad');
				} else {

					$email = new CakeEmail();
					$email->config('smtp');
					$email->emailFormat('html');
					$email->template('default');

					$email->from(array('no-reply@dtu-lan.dk' => 'DTU LAN Party'));
					$email->to($user['User']['email']);
					$email->subject('DTU LAN site - Password reset');

					$id = $this->User->UserPasswordTicket->getLastInsertID();

					$msg = '';
					$msg.='<h2>Hey ' . $user['User']['name'] . '</h2>';
					$msg.='This is an autogenerated email from the DTU LAN site.';
					$msg.='<br />';
					$msg.='To reset your password, please follow the "reset password"-link below.';
					$msg.='<br />';
					$msg.='<a href="http://dtu-lan.dk/users/reset_password/' . $id . '">http://dtu-lan.dk/users/reset_password/' . $id . '</a>';
					$msg.='<br /><br />';
					$msg.='Best regards';
					$msg.='<br />';
					$msg.='The DTU LAN Crew';

					if ($email->send($msg)) {
						$this->Session->setFlash(__('Email sent'), 'default', array('class' => 'message success'), 'good');
					} else {
						$this->Session->setFlash('Fatal error during sending. Please try again. ' . $msg, 'default', array(), 'bad');
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

		$this->layout = 'ajax';
		$users = array();

		if ($this->request->is('post')) {

			$input_string = $this->request->data['search_startsWith'];

			$this->User->recursive = 0;
			$users = $this->User->find('all', array(
				'conditions' => array(
					'OR' => array(
						array(
							'User.name LIKE' => '%' . $input_string . '%'
						),
						array(
							'User.email LIKE' => '%' . $input_string . '%'
						),
						array(
							'User.id_number LIKE' => '%' . $input_string . '%'
						),
					)
				)
					)
			);

			$users = array('users' => $users);
		}
		$this->set(compact('users'));
	}

}