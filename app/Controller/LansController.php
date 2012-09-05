<?php

class LansController extends AppController {

	public $helpers = array('Html', 'Form');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view', 'menu', 'menuItem');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if (isset($user['Admin']['user_id'])) {
			return true;
		} else {
			return false;
		}
	}

	public function index() {
		$this->set('lans', $this->Lan->find('all'));
	}

	public function viewPastLans() {
		$currentTime = date('Y-m-d H:i:s');
		$data = $this->Lan->find('all', array(
			'conditions' => array(
				'Lan.published' => 1,
				'Lan.time_end <' => $currentTime
				)));

		$this->set('lans', $data);
		$this->view = 'index';
	}

	public function viewCurrentLans() {
		$currentTime = date('Y-m-d H:i:s');
		$data = $this->Lan->find('all', array(
			'conditions' => array(
				'Lan.time_end >' => $currentTime,
				'Lan.time_start <' => $currentTime
				)));
		$this->set('lans', $data);
		$this->view = 'index';
	}

	public function viewFutureLans() {
		$currentTime = date('Y-m-d H:i:s');
		$data = $this->Lan->find('all', array(
			'conditions' => array(
				'Lan.time_start >' => $currentTime
				)));
		$this->set('lans', $data);
		$this->view = 'index';
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->request->data['LanDay'] = $this->Lan->getLanDays($this->request->data['Lan']['time_start'], $this->request->data['Lan']['time_end']);


			if ($this->Lan->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your Lan has been saved.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your lan.');
			}
		}
	}

	public function edit($id = null) {
		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException(__('Invalid Lan'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Lan->save($this->request->data)) {
				$this->Session->setFlash(__('The LAN has been saved'));
//				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Lan could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Lan->read(null, $id);
		}
	}

	public function view($id = null) {
		$this->Lan->id = $id;

		$this->Lan->recursive = 2;
		$this->set('lan', $this->Lan->read());

		if ($this->Auth->loggedIn()) {

			$user = $this->Auth->user();

			if ($this->request->is('post')) {
				$this->request->data['LanInvite']['lan_id'] = $id;
				$this->request->data['LanInvite']['user_student_id'] = $user['id'];
				$this->request->data['LanInvite']['time_invited'] = date('Y-m-d H:i:s');

				if ($this->Lan->LanInvite->save($this->request->data)) {
					$this->Session->setFlash('Your invite has been sent');
				} else {
					$this->Session->setFlash('Unable to send your invite');
				}
			}

			if ($user['type'] == 'student') {
				$user_guests = $this->Lan->getInviteableUsers($id);
//				debug($user_guests);
				$this->set(compact('user_guests'));
			}
		}
	}

	public function lookup($id = null) {
		$this->Lan->id = $id;

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$this->Lan->recursive = 0;
		//$this->set('lan', $this->Lan->read());

		$this->Lan->Behaviors->attach('Containable');
		$this->Lan->contain(array('LanSignup' => array('User')));
		$this->set('lan', $this->Lan->read());
	}

}

?>
