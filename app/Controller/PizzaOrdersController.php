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

	public function add() {
		if (!$this->request->is('ajax')) {
			throw new BadRequestException('Bad request from client');
		}

		$data = $this->request->data;

		$user = $this->Auth->user();

		$this->request->data['PizzaOrder'] = array(
			'pizza_wave_id' => $data['wave_id'],
			'user_id' => $user['id']
		);

		$pizza_sum = 0;
		foreach ($data['order_list'] as $pizza_price_id => $pizza_data) {
			$this->request->data['PizzaOrderItem'][] = array(
				'pizza_price_id' => $pizza_price_id,
				'amount' => $pizza_data['amount'],
				'price' => $pizza_data['price_value']
			);

			$pizza_sum += $pizza_data['amount'] * $pizza_data['price_value'];
		}
//
		unset($this->request->data['order_list'], $this->request->data['wave_id']);

		$this->request->data['User']['id'] = $user['id'];
		$this->request->data['User']['balance'] = $user['balance'] - $pizza_sum;

		if (isset($this->request->data['PizzaOrderItem']) && count($this->request->data['PizzaOrderItem'])) {

			if ($this->PizzaOrder->saveAssociated($this->request->data)) {
				$msg = 'SUCCESS';
			} else {
				$msg = $this->PizzaOrder->validationErrors;
			}
		} else {
			$msg = 'Invalid pizza order';
		}

		$this->set(compact('msg'));
	}

}

?>
