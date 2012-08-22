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

		$this->Auth->allow('login', 'add', 'activate');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if (in_array($this->action, array('index', 'profile', 'editPersonalData', 'logout'))) {
			return true;
		} elseif (in_array($this->action, array('activate', 'add', 'login'))) {
			return false;
		}
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

		$this->User->recursive = 3;
		$user = $this->User->read();

		$this->set('user', $user);

		$lan_ids = array();
		foreach($user['LanSignup'] as $lan){
			$lan_ids[] = $lan['lan_id'];
		}

		$next_lan = ClassRegistry::init('Lan')->find('first', array(
			'conditions' => array(
				'Lan.sign_up_open' => 1,
				'Lan.time_end >' => date('Y-m-d H:i:s'),
				'NOT' => array(
					'Lan.id' => $lan_ids
				)
			),
			'order' => array('Lan.time_end ASC'),
			'recursive' => 0
				)
		);

		$this->set(compact('next_lan'));
	}

	public function add() {
		if ($this->request->is('post')) {

			$this->request->data['User']['time_created'] = date('Y-m-d H:i:s');

			//$this->User->create();

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

//				$email = new CakeEmail();
//				$email->from(array('admin@DTU-Lan.dk' => 'DTU-Lan'));
//				$email->to($this->request->data['User']['email']);
//				$email->subject('DTU-LAN site - Activation');
//				$email->send($msg);
				//echo $msg;

				$this->Session->setFlash(__('The user has been made. Check your email to continue the activation process. ' . $msg));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please try again.'));
			}
		}
	}

	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}
	}

	public function editPersonalData() {
		$user = $this->Auth->user();
		$this->User->id = $user['id'];

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Your data has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The data could not be saved. Please try again.'));
			}
		} else {
			$this->request->data = $this->User->read();
		}
	}

//	public function delete($id = null) {
//		if (!$this->request->is('post')) {
//			throw new MethodNotAllowedException();
//		}
//		$this->User->id = $id;
//		if (!$this->User->exists()) {
//			throw new NotFoundException(__('Invalid user'));
//		}
//		if ($this->User->delete()) {
//			$this->Session->setFlash(__('User deleted'));
//			$this->redirect(array('action' => 'index'));
//		}
//		$this->Session->setFlash(__('User was not deleted'));
//		$this->redirect(array('action' => 'index'));
//	}

	public function activate($id = null) {
		//$this->User->setValidation('activate');

		$this->User->id = $id;

		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->User->read();

		if ($user['User']['activated'] != 0) {
			throw new NotFoundException(__('Invalid user'));
		}

		if ($this->request->is('post')) {

			$this->request->data['User']['activated'] = 1;
			$this->request->data['User']['time_activated'] = date('Y-m-d H:i:s');

			if ($this->User->save($this->request->data)) {
				/*
				 * Logs user in after successful activation
				 */
				$this->Auth->login($this->User);
				$this->Session->setFlash(__('User activated. Welcome'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('User was not activated'));
		}
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->Session->setFlash(__('You are now logged in'));

				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Your username/password combination was incorrect'));
			}
		}
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

}

?>
