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

	public function add() {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request from client');
		}

		$data = $this->request->data;

		if (!$this->PizzaOrder->PizzaWave->isOrderable($data['wave_id'], $this->Auth->user('id'))) {
			$msg = 'Pizza wave not valid';
		} else {
			$this->request->data['PizzaOrder'] = array(
				'pizza_wave_id' => $data['wave_id'],
				'user_id' => $this->Auth->user('id')
			);

			// Get data from the pizza wave
			$this->PizzaOrder->PizzaWave->read(null, $data['wave_id']);

			// Get the user balance
			$this->PizzaOrder->User->read(array('balance'), $this->Auth->user('id'));

			// Used to calc the total price for pizzas
			$pizza_sum = 0;

			// Manipulating the input data - running each pizza
			foreach ($data['order_list'] as $pizza_price_id => $pizza_data) {
				$this->request->data['PizzaOrderItem'][] = array(
					'pizza_price_id' => $pizza_price_id,
					'amount' => $pizza_data['amount'],
					'price' => $pizza_data['price_value']
				);

				$pizza_sum += $pizza_data['amount'] * $pizza_data['price_value'];
			}

			// Unset the "old" data, which was the input from the client
			unset($this->request->data['order_list'], $this->request->data['wave_id']);

			// Edit the user balance
			$this->request->data['User']['id'] = $this->Auth->user('id');
			$this->request->data['User']['balance'] = $this->PizzaOrder->User->data['User']['balance'] - $pizza_sum;

			// Is there any pizzas to order?
			if (isset($this->request->data['PizzaOrderItem']) && count($this->request->data['PizzaOrderItem'])) {

				if ($this->PizzaOrder->saveAssociated($this->request->data)) {
					$msg = 'SUCCESS';
				} else {
					$msg = $this->PizzaOrder->validationErrors;
				}
			} else {
				$msg = 'Invalid pizza order';
			}
		}



		$this->set(compact('msg'));
	}

	public function set_status($pizza_order_id = null) {
		$this->PizzaOrder->id = $pizza_order_id;

		if (!$this->PizzaOrder->exists()) {
			throw new NotFoundException('Pizza order not found');
		}
	}

}

?>
