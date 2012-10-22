<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaOrdersController
 *
 * @author Jens
 */
class FoodOrdersController extends AppController {

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)) {
			return true;
		} elseif (in_array($this->action, array(
					'add',
					'delete',
				))) {
			return true;
		}

		return false;
	}

	public function index() {
		$this->paginate = array(
			'limit' => 5,
			'recursive' => 3
		);

		$orders = $this->paginate('FoodOrder');

		$this->set(compact('orders'));
	}

	public function add() {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request from client');
		}

		$data = $this->request->data;

		$this->request->data['FoodOrder'] = array(
			'lan_id' => $data['lan_id'],
			'user_id' => $this->Auth->user('id')
		);

		// Get the user balance
		$this->FoodOrder->User->read(array('balance'), $this->Auth->user('id'));

		// Used to calc the total price for food
		$sum = 0;

		// Manipulating the input data - running each pizza
		foreach ($data['order_list'] as $item_id => $item) {
			$this->request->data['FoodOrderItem'][] = array(
				'food_id' => $item_id,
				'quantity' => $item['quantity'],
				'price' => $item['price']
			);

			$sum += $item['quantity'] * $item['price'];
		}

		// Unset the "old" data, which was the input from the client
		unset($this->request->data['order_list'], $this->request->data['wave_id']);

		// Edit the user balance
		$this->request->data['User']['id'] = $this->Auth->user('id');
		$this->request->data['User']['balance'] = $this->FoodOrder->User->data['User']['balance'] - $sum;

		// Is there any item to order?
		if (count($this->request->data['FoodOrderItem'])) {
			if ($this->FoodOrder->saveAssociated($this->request->data)) {
				$msg = 'SUCCESS';
			} else {
				$msg = $this->FoodOrder->validationErrors;
			}
		} else {
			$msg = 'Invalid order';
		}


		$this->set(compact('msg'));
	}

	public function mark_delivered($id = null) {
		$this->FoodOrder->id = $id;

		if (!$this->FoodOrder->exists()) {
			throw new NotFoundException('Order not found');
		}

		$this->FoodOrder->read(array('status'));

		if ($this->FoodOrder->data['FoodOrder']['status'] == 1) {
			$this->Session->setFlash('Order already marked as delivered', 'default', array('class' => 'message success'), 'good');
		} else {
			if ($this->FoodOrder->saveField('status', 1, true)) {
				$this->Session->setFlash('Order marked as delivered', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to mark order as delivered', 'default', array(), 'bad');
			}
		}

		$this->redirect(array(
			'controller' => 'food_orders',
			'action' => 'index'
				)
		);
	}

	public function delete($id) {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Bad request from client');
		}

		$this->FoodOrder->id = $id;

		if (!$this->FoodOrder->exists()) {
			throw new NotFoundException(__('Order not found'));
		}

		$this->FoodOrder->read(array('user_id', 'status'));

		if ($this->FoodOrder->data['FoodOrder']['user_id'] != $this->Auth->user('id')) {
			throw new NotFoundException(__('Order not found'));
		}

		if ($this->FoodOrder->data['FoodOrder']['status'] > 0) {
			throw new UnauthorizedException(__('Order already delivered'));
		}

		$sum = $this->FoodOrder->FoodOrderItem->find('all', array(
			'fields' => array(
				'sum(FoodOrderItem.quantity * FoodOrderItem.price) AS ctotal'
			),
			'conditions' => array(
				'FoodOrderItem.food_order_id' => $id
			)
				)
		);

		$sum = $sum[0][0]['ctotal'];

		$this->FoodOrder->User->id = $this->Auth->user('id');
		$this->FoodOrder->User->read(array('balance'));

		$new_balance = $this->Foodrder->User->data['User']['balance'] + $sum;

		if ($this->FoodOrder->User->saveField('balance', $new_balance, true) && $this->FoodOrder->delete()) {
			$this->Session->setFlash('Order cancelled', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('controller' => 'users', 'action' => 'profile'));
		} else {
			$this->Session->setFlash('Order could not be cancelled', 'default', array(), 'bad');
			$this->redirect(array('controller' => 'users', 'action' => 'profile'));
		}
	}

}

?>
