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

		if ($this->isAdmin($user)) {
			return true;
		} elseif (in_array($this->action, array(
			'profile',
			'logout',
//			'editPersonalData',
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
			$id = $this->Auth->user();
		}

		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

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
		$this->User->LanSignup->LanSignupDay->unbindModel(array('hasMany' => array('LanSignup', 'Tournament', 'PizzaWave')));

		$lans = $this->User->LanSignup->find('all', array('conditions' => array(
				'LanSignup.user_id' => $id
			)
				)
		);

//		$this->User->LanInvite->recursive = 2;
		$this->User->LanInvite->unbindModel(array('belongsTo' => array('Guest')));

		$lan_invites = $this->User->LanInvite->find('first', array('conditions' => array(
				'LanInvite.user_guest_id' => $id,
				'LanInvite.accepted' => 0
			)
				)
		);

		$this->User->unbindModel(array('hasMany' => array('PizzaOrder', 'LanSignup')));
		$user = $this->User->read();

		$this->set('user', $user);

		$lan_ids = array();
		foreach ($lans as $lan) {
			$lan_ids[] = $lan['Lan']['id'];
		}

		if ($user['User']['type'] == 'student') {
			$this->set('next_lan', $this->User->LanSignup->Lan->find('first', array(
						'conditions' => array(
							'Lan.sign_up_open' => 1,
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

		$title_for_layout = 'Profile &bull; '.$user['User']['name'];

		$this->set(compact('pizza_orders', 'lans', 'teams', 'lan_invites', 'title_for_layout'));
	}

	public function add() {
		if ($this->request->is('post')) {

			$this->request->data['User']['time_created'] = date('Y-m-d H:i:s');

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
//				$this->redirect(array('action' => 'index'));
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
		if ($this->request->is('post')) {

                $this->User->unbindModel(
                        array(
                            'hasMany' => array('Payment', 'PizzaOrder', 'Crew', 'LanSignup', 'TeamInvite', 'TeamUser', 'LanInvite', 'LanInviteSent'),
                            'hasOne' => array('Admin')
                        )
                    );
                
                $this->User->read(array('id', 'activated', 'email'), $id);

			if (!$this->User->exists()) {
				throw new NotFoundException(__('Invalid user'));
			}

			if ($this->User->isActivated()) {
				throw new NotFoundException(__('Invalid user'));
			}

			// Setting fields in $user for saving
			$this->User->set(
					array(
						'activated' => 1,
						'time_activated' => date('Y-m-d H:i:s'),
						'password' => $this->request->data['User']['password'],
						'password_confirmation' => $this->request->data['User']['password_confirmation']
					)
			);

			if ($this->User->save()) {
				/*
				 * Logs user in after successful activation
				 */
				$this->Auth->login($this->User->id);
				$this->Session->setFlash(__('User activated. Welcome'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('User was not activated'));
			}
		}

		if ($this->User->isActivated()) {
			throw new NotFoundException(__('Invalid user'));
		}
           
                // Setting fields in $user for saving
                $this->User->set(
                    array(
                        'activated' => 1,
                        'time_activated' => date('Y-m-d H:i:s'),
                        'password' => $this->request->data['User']['password'],
                        'password_confirmation' => $this->request->data['User']['password_confirmation']
                    )
                );
//                debug($this->User->data);
                if ($this->User->save()) {
                    /*
                     * Logs user in after successful activation
                     */
                    // This should login the user. Have not tried it out
                    $data['User'] = $this->User->id;
                    $data['User'] = array_merge($data['User'], array('password' => $this->request->data['User']['password']));
                    $data['User'] = array_merge($data['User'], array('email' => $this->User->data['User']['email']));
                    
                    $this->Auth->login($data);
                    $this->Session->setFlash(__('User activated. Welcome'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('User was not activated'));
                }
            }
	}

	public function login() {
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
