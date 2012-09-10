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
		if (!$this->request->is('post')) {
			throw new BadRequestException('Invalid request');
		}
		else{
			$this->Payment->User->read(array('balance'), $this->request->data['Payment']['user_id']);

			$this->request->data['User']['id'] = $this->request->data['Payment']['user_id'];
			$this->request->data['User']['balance'] = $this->Payment->User->data['User']['balance'] + $this->request->data['Payment']['amount'];

			if ($this->Payment->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your payment has been saved', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to add your payment.', 'default', array(), 'bad');
			}
		}

		$this->redirect($this->referer());
	}
}

?>
