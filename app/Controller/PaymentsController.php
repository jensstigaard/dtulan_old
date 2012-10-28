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

	public function index_user($user_id){
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request');
		}

		$this->layout = 'ajax';

		$this->Payment->User->id = $user_id;

		if (!$this->Payment->User->exists()) {
			throw new NotFoundException('User not found with ID #' . $user_id);
		}

		$this->paginate = array(
			'Payment' => array(
				'conditions' => array(
					'Payment.user_id' => $user_id,
				),
				'recursive' => 0,
				'limit' => 10,
				'order' => array(
					array('Payment.time' => 'desc')
				)
			),
		);

		$payments = $this->paginate('Payment');

		$this->Payment->dateToNiceArray($payments, 'Payment');

		$this->set(compact('payments'));
	}


	public function index() {

		$this->paginate = array(
			'limit' => 10,
			'recursive' => 2,
			'order' => array(
				'time' => 'desc'
			)
		);

		$payments = $this->paginate('Payment');

		$this->Payment->dateToNiceArray($payments, 'Payment');

		$this->set(compact('payments'));
	}

	public function add() {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Invalid request');
		} else {
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
