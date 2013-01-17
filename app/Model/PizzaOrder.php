<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaOrder
 *
 * @author Jens
 */
class PizzaOrder extends AppModel {

	public $hasMany = array(
		'PizzaOrderItem' => array(
			'dependent' => true
		)
	);
	public $belongsTo = array('User', 'PizzaWave');
	public $order = array(
		'time' => 'desc'
	);
	public $validate = array(
		'pizza_wave_id' => array(
			'exists' => array(
				'rule' => 'validWave',
				'message' => 'Wave is not valid'
			)
		)
	);

	public function validWave($check) {
		$count = $this->PizzaWave->find('count', array(
			'conditions' => array(
				'PizzaWave.id' => $check['pizza_wave_id'],
				'PizzaWave.time_end >' => date('Y-m-d H:i:s'),
			)
				)
		);

		if ($count == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function isCancelable() {

		if (!$this->exists()) {
			throw new NotFoundException('Pizza order not found! ID: ' . $this->id);
		}

		$this->read(array('pizza_wave_id', 'status'));

		$this->PizzaWave->id = $this->data['PizzaOrder']['pizza_wave_id'];

		return $this->PizzaWave->isOrderable();
	}

	public function getPizzaOrdersByUser($id = '') {
		return $this->find('all', array(
					'conditions' => array(
						'PizzaOrder.user_id' => $id,
						'PizzaWave.status' => 3
					),
					'recursive' => 4
						)
		);
	}

	/*
	 * 
	 * Try to place a PizzaOrder
	 * 
	 * Required
	 * 	- $data_input, with following format:
	 * 		array(
	 * 			'PizzaOrder' => array(
	 * 				'user_id' => ?
	 * 				'pizza_wave_id' => ?
	 * 			)
	 * 			'PizzaOrderItem' => array(
	 * 				0 => array(
	 * 					'pizza_price_id' => ?
	 * 					'quantity' => ?
	 * 					'price' => ?
	 * 				),
	 * 				
	 * 			)
	 * 		)
	 */

	public function saveOrder($data_input) {

		$success = false;

		if ($this->save($data_input)) {
			$success = true;
		} else {
			if (isset($this->PizzaOrder->validationErrors['User']['balance'][0])) {
				$message = $this->PizzaOrder->validationErrors['User']['balance'][0];
			} else {
				$message = $this->PizzaOrder->validationErrors;
			}
		}

		return array(
			'success' => $success,
			'message' => $message
		);
	}

	public function manipulateInputData($data_input) {
		$data = array(
			'DataSave' => array(
				'PizzaOrder' => array(
					'pizza_wave_id' => $data_input['wave_id'],
					'user_id' => $this->getLoggedInId()
				),
			),
			'PizzaOrderItemSum' => 0
		);

		foreach ($data_input['order_list'] as $pizza_price_id => $pizza_data) {
			$data['DataSave']['PizzaOrderItem'][] = array(
				'pizza_price_id' => $pizza_price_id,
				'quantity' => $pizza_data['quantity'],
				'price' => $pizza_data['price_value']
			);

			$data['PizzaOrderItemSum'] += $pizza_data['quantity'] * $pizza_data['price_value'];
		}

		return $data;
	}

}

?>
