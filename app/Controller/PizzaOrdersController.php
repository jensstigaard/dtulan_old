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
class PizzaOrdersController extends AppController {

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->PizzaOrder->isYouAdmin() || in_array($this->action, array(
					'add',
					'delete',
				))) {
			return true;
		}
		return false;
	}

	public function add() {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request from client');
		}

		$data = $this->request->data;

		$this->PizzaOrder->PizzaWave->id = $this->request->data['wave_id'];

		if (!$this->PizzaOrder->PizzaWave->isOrderable()) {
			$msg = 'Pizza wave not valid';
		} else {
			$this->request->data['PizzaOrder'] = array(
				'pizza_wave_id' => $this->request->data['wave_id'],
				'user_id' => $this->Auth->user('id')
			);

			// Get data from the pizza wave
			$this->PizzaOrder->PizzaWave->read(null, $data['wave_id']);

			// Used to calc the total price for pizzas
			$pizza_sum = 0;

			// Manipulating the input data - running each pizza
			foreach ($data['order_list'] as $pizza_price_id => $pizza_data) {
				$this->request->data['PizzaOrderItem'][] = array(
					'pizza_price_id' => $pizza_price_id,
					'quantity' => $pizza_data['quantity'],
					'price' => $pizza_data['price_value']
				);

				$pizza_sum += $pizza_data['quantity'] * $pizza_data['price_value'];
			}

			// Unset the "old" data, which was the input from the client
			unset($this->request->data['order_list'], $this->request->data['wave_id']);

			// Edit the user balance
			$this->PizzaOrder->User->id = $this->Auth->user('id');
			$this->request->data['User']['id'] = $this->Auth->user('id');
			$this->request->data['User']['balance'] = $this->PizzaOrder->User->getBalance() - $pizza_sum;

			// Is there any pizzas to order?
			if (isset($this->request->data['PizzaOrderItem']) && count($this->request->data['PizzaOrderItem'])) {

				if ($this->PizzaOrder->saveAssociated($this->request->data)) {
					$msg = 'SUCCESS';
				} else {
					if (isset($this->PizzaOrder->validationErrors['User']['balance'][0])) {
						$msg = $this->PizzaOrder->validationErrors['User']['balance'][0];
					} else {
						$msg = $this->PizzaOrder->validationErrors;
					}
				}
			} else {
				$msg = 'Invalid pizza order';
			}
		}

		$this->set(compact('msg'));
	}

	public function api_edit($id = '') {
		if ($this->request->is('put')) {
			if ($this->isJsonRequest()) {
				$this->PizzaOrder->id = $id;
				if ($this->PizzaOrder->exists() && isset($this->request->data['PizzaOrder']['status'])) {
					$this->PizzaOrder->recursive = 0;
					$this->PizzaOrder->read();
					$this->PizzaOrder->data['PizzaOrder']['status'] = $this->request->data['PizzaOrder']['status'];
					if ($this->PizzaOrder->save()) {
						$this->set('success', true);
						$this->set('data', array('message' => __('Pizza order status: ' . $this->request->data['PizzaOrder']['status'])));
					} else {
						$this->set('success', false);
						$this->set('data', array('message' => __('Unable to update pizza order')));
					}
					$this->set('_serialize', array('success', 'data'));
				} else {
					throw new HttpInvalidParamException;
				}
			} else {
				throw new BadRequestException;
			}
		} else {
			throw new MethodNotAllowedException;
		}
	}

	public function mark_delivered($id = null) {
		$this->PizzaOrder->id = $id;

		if (!$this->PizzaOrder->exists()) {
			throw new NotFoundException('Pizza order not found');
		}

		$this->PizzaOrder->read(array('status', 'pizza_wave_id'));

		$wave_id = $this->PizzaOrder->data['PizzaOrder']['pizza_wave_id'];

		if ($this->PizzaOrder->data['PizzaOrder']['status'] == 1) {
			$this->Session->setFlash('Pizza order already marked as delivered', 'default', array('class' => 'message success'), 'good');
		} else {
			if ($this->PizzaOrder->saveField('status', 1, true)) {
				$this->Session->setFlash('Pizza order marked as delivered', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to mark pizza order as delivered', 'default', array(), 'bad');
			}
		}

		$this->redirect(array(
			'controller' => 'pizza_waves',
			'action' => 'view',
			$wave_id
				)
		);
	}

	public function delete($id) {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Bad request from client');
		}

		$this->PizzaOrder->id = $id;

		if (!$this->PizzaOrder->exists()) {
			throw new NotFoundException(__('Pizza order not found'));
		}

		$this->PizzaOrder->read(array('user_id', 'pizza_wave_id', 'status'));

		if ($this->PizzaOrder->data['PizzaOrder']['user_id'] != $this->Auth->user('id')) {
			throw new NotFoundException(__('Pizza order not found'));
		}

		if ($this->PizzaOrder->data['PizzaOrder']['status'] > 0) {
			throw new UnauthorizedException(__('Pizza order already delivered'));
		}

		if (!$this->PizzaOrder->PizzaWave->isOrderable($this->PizzaOrder->data['PizzaOrder']['pizza_wave_id'], $this->isAdmin())) {
			throw new UnauthorizedException(__('It is not possible to delete pizza order anymore'));
		}

		$sum = $this->PizzaOrder->PizzaOrderItem->find('all', array(
			'fields' => array(
				'sum(PizzaOrderItem.quantity * PizzaOrderItem.price) AS ctotal'
			),
			'conditions' => array(
				'PizzaOrderItem.pizza_order_id' => $id
			)
				)
		);

		$sum = $sum[0][0]['ctotal'];

		$this->PizzaOrder->User->id = $this->Auth->user('id');
		$this->PizzaOrder->User->read(array('balance'));

		$new_balance = $this->PizzaOrder->User->data['User']['balance'] + $sum;

		if ($this->PizzaOrder->User->saveField('balance', $new_balance, true) && $this->PizzaOrder->delete()) {
			$this->Session->setFlash('Pizza order cancelled', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('controller' => 'users', 'action' => 'profile'));
		} else {
			$this->Session->setFlash('Pizza order could not be cancelled', 'default', array(), 'bad');
			$this->redirect(array('controller' => 'users', 'action' => 'profile'));
		}
	}

	public function api_view($id) {
		if ($this->request->is('get')) {
			if ($this->isJsonRequest()) {
				$this->PizzaOrder->id = $id;
				if ($this->Pizzaorder->exists()) {
					$this->PizzaOrder->read();
					$pizza_order_items = debug($this->PizzaOrder->data);
					$this->set('success', true);
					$this->set('data', array('pizza_order_items' => $pizza_order_items));
					$this->set_status('_serialize', array('success', 'data'));
				} else {
					throw new NotFoundException(__('Invalid pizza order'));
				}
			} else {
				throw new BadRequestException;
			}
		} else {
			throw new MethodNotAllowedException;
		}
	}

}

?>
