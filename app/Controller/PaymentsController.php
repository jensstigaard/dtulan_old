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
				$this->Session->setFlash('Your payment has been saved');
			} else {
				$this->Session->setFlash('Unable to add your payment.');
			}
		}

		$this->redirect($this->referer());
	}
}

?>
