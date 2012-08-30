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

		foreach($data['order_list'] as $pizza_price_id => $pizza_data){
			$this->request->data['PizzaOrderItem'][] = array(
				'pizza_price_id' => $pizza_price_id,
				'amount' => $pizza_data['amount'],
				'price' => $pizza_data['price_value']
			);
		}

		$this->request->data['order_list'] = array();

//		$this->set('data', $this->request->data);
//		$this->set('old_data', $data);

		if($this->PizzaOrder->saveAssociated($this->request->data)){
			$this->set('error', 0);
		}
	}

}

?>
