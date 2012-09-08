<?php

class PaymentsController extends AppController {

	public $helpers = array('Html', 'Form');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if (in_array($this->action, array('add', 'view')) ||
				$this->isAdmin($user)) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->set('payments', $this->Payment->find('all'));
	}

	public function add() {
		if ($this->request->is('post')) {
			if ($this->Payment->save($this->request->data)) {
				$this->Session->setFlash('Your payment has been saved.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your payment.');
			}
		}

		$opt = $this->Payment->User->find('list', array('order' => 'name'));

		$this->set('users', $opt);
	}

	public function edit($id = null) {
		$this->Lan->id = $id;
		if (!$this->Lan->exists()) {
			throw new NotFoundException(__('Invalid Lan'));
		}

		$this->set('lan', $this->Lan->read(null, $id));

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Lan->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Lan could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Lan->read(null, $id);
		}
	}

	public function view($id = null) {
		$this->Lan->id = $id;

		$this->Lan->recursive = 1;
		$this->set('lan', $this->Lan->read());
	}

//	In use?
	public function lookup($id = null) {
		$this->Lan->id = $id;

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$this->Lan->recursive = -1;
		//$this->set('lan', $this->Lan->read());

		$this->Lan->Behaviors->attach('Containable');
		$this->Lan->contain(array('LanSignup' => array('User')));
		$this->set('lan', $this->Lan->read());
	}

}

?>
