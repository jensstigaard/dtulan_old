<?php

class PaymentsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if (in_array($this->action, array('add', 'view')) ||
				$this->Payment->isYouAdmin()) {
			return true;
		}
		return false;
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

			$types = array(
				'c' => 'cash',
				'k' => 'creditcard',
				'm' => 'mobilepay',
			);

			if (preg_match('/^(c|m|k)(-)?[0-9]{1,3}$/', strtolower($this->request->data['Payment']['amount']))) {
				$this_type = substr($this->request->data['Payment']['amount'], 0, 1);
				$this->request->data['Payment']['type'] = $types[$this_type];
				$this->request->data['Payment']['amount'] = substr($this->request->data['Payment']['amount'], 1);
			}

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
