<?php

class LansController extends AppController {

	public $helpers = array('Html', 'Form');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user) || in_array($this->action, array('view'))) {
			return true;
		}
		return false;
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

	public function view($id = null) {

		$this->Lan->recursive = 2;

		$cond['Lan.id'] = $id;
		if ($this->Auth->loggedIn()) {
			$user = $this->Auth->user();
			if (!$this->isAdmin($user)) {
				$cond['Lan.published'] = 1;
			}
		}
		$lan = $this->Lan->find('first', array('conditions' => $cond));

		if (!isset($lan['Lan'])) {
			throw new NotFoundException('No LAN found');
		}

		$title_for_layout = 'Lan &bull; '.$lan['Lan']['title'];

		$this->set(compact('lan', 'title_for_layout'));

		if (isset($user)) {

			if ($this->request->is('post')) {
				$this->request->data['LanInvite']['lan_id'] = $id;
				$this->request->data['LanInvite']['user_student_id'] = $user['id'];
				$this->request->data['LanInvite']['time_invited'] = date('Y-m-d H:i:s');

				if ($this->Lan->LanInvite->save($this->request->data)) {
					$this->Session->setFlash('Your invite has been sent', 'default', array('class' => 'message success'), 'good');
				} else {
					$this->Session->setFlash('Unable to send your invite', 'default', array(), 'bad');
				}
			}

			if ($user['type'] == 'student') {
				$user_guests = $this->Lan->getInviteableUsers($id);
				$this->set(compact('user_guests'));
			}
		}
	}

	public function add() {
		if ($this->request->is('post')) {
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

		if ($this->request->is('post')) {
			if ($this->Lan->save($this->request->data)) {
				$this->Session->setFlash(__('The LAN has been saved'), 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Lan could not be saved. Please, try again.'), 'default', array(), 'bad');
			}
		} else {
			$this->request->data = $this->Lan->read(null, $id);
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