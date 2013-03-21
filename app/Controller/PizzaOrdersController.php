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

	public function index() {

		$conditions['PizzaOrder.pizza_wave_id'] = $this->request->query['pizza_wave_id'];

		if (isset($this->request->query['only_not_delivered']) && $this->request->query['only_not_delivered'] == 'true') {
			$conditions['PizzaOrder.status'] = '0';
		}
		
		if (!empty($this->request->query['user'])) {
			$conditions['PizzaOrder.user_id'] = $this->PizzaOrder->User->getUserIdsByLike($this->request->query['user']);
		}

		$pizza_orders = $this->PizzaOrder->find('all', array(
			 'conditions' => $conditions,
			 'order' => array(
				  'status' => 'asc',
				  'time' => 'asc'
			 ),
			 'contain' => array(
				  'User',
				  'PizzaOrderItem' => array(
						'PizzaPrice' => array(
							 'Pizza',
							 'PizzaType'
						)
				  )
			 )
				  ));
		
		$this->PizzaOrder->dateToNiceArray($pizza_orders, 'PizzaOrder');

		$this->set(compact('pizza_orders'));
		$this->set('_serialize', array('pizza_orders'));
	}

	public function add() {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request from client');
		}

		$this->PizzaOrder->User->id = $this->PizzaOrder->getLoggedInId();

		$this->set('data', $this->PizzaOrder->saveOrder($this->request->data));
		$this->set('_serialize', array('data'));
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
		$this->PizzaOrder->User->id = $this->PizzaOrder->getLoggedInId();

		if ($this->PizzaOrder->deleteOrder()) {
			$this->Session->setFlash('Pizza order cancelled', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('controller' => 'users', 'action' => 'view'));
		} else {
			$this->Session->setFlash('Pizza order could not be cancelled', 'default', array(), 'bad');
			$this->redirect(array('controller' => 'users', 'action' => 'view'));
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
