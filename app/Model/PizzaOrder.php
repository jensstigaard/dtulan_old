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
	public $belongsTo = array(
		 'User',
		 'PizzaWave'
	);
	public $order = array(
		 'time' => 'desc'
	);
	public $validate = array(
		 'pizza_wave_id' => array(
			  'exists' => array(
					'rule' => 'validateWaveExists',
					'message' => 'Wave is not valid'
			  ),
			  'valid wave and user' => array(
					'rule' => 'validateWaveUser',
					'message' => 'You have not access to this wave'
			  )
		 )
	);

	public function validateWaveExists($check) {
		$this->PizzaWave->id = $check['pizza_wave_id'];

		return $this->PizzaWave->exists();
	}

	public function validateWaveUser($check) {
		$this->PizzaWave->id = $check['pizza_wave_id'];
		$this->User->id = $this->data['PizzaOrder']['user_id'];

		if (!$this->PizzaWave->exists()) {
			throw new NotFoundException('PizzaWave not found');
		}

		if (!$this->User->exists()) {
			throw new NotFoundException('User not found with id: ' . $this->User->id);
		}

		return $this->PizzaWave->isUserConnected();
	}

	public function isCancelable() {

		if (!$this->exists()) {
			throw new NotFoundException('Pizza order not found! ID: ' . $this->id);
		}

		$this->read(array('pizza_wave_id', 'status'));

		$this->PizzaWave->id = $this->data['PizzaOrder']['pizza_wave_id'];

		return $this->PizzaWave->isOrderable();
	}

	// Refactor function ??
	// Eventually move to User-model
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
	 * 
	 * 
	 * Output:
	 *      array(
	 *          'success' => ?,
	 *          'message' => ?,
	 * 
	 * 
	 */

	public function saveOrder($data_input) {

		$success = false;
		$message = false;

		$data = $this->manipulateInputData($data_input);

		$dataSource = $this->getDataSource();
		$dataSource->begin();

		if ($this->saveAssociated($data['DataSave']) && $this->User->balanceDecrease($data['PizzaOrderItemSum'])) {
			$dataSource->commit();
			$success = true;
		} else {
			$dataSource->rollback();

			if (isset($this->validationErrors['User']['balance'][0])) {
				$message = $this->validationErrors['User']['balance'][0];
			} else {
				$message = $this->validationErrors;
			}
		}

		return array(
			 'success' => $success,
			 'message' => $message
		);
	}

	/*
	 * 
	 * Manipulate (ajax) input data to CakePHP-data syntax
	 */

	public function manipulateInputData($data_input) {
		$data = array(
			 'DataSave' => array(
				  'PizzaOrder' => array(
						'pizza_wave_id' => $data_input['wave_id'],
						'user_id' => $this->User->id
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

	public function getItemsSum() {

		$sum = $this->PizzaOrderItem->find('all', array(
			 'fields' => array(
				  'sum(PizzaOrderItem.quantity * PizzaOrderItem.price) AS ctotal'
			 ),
			 'conditions' => array(
				  'PizzaOrderItem.pizza_order_id' => $this->id
			 )
				  )
		);

		return $sum[0][0]['ctotal'];
	}

	public function deleteOrder() {
		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza order not found'));
		}

		$this->read(array('pizza_wave_id'));

		$this->PizzaWave->id = $this->data['PizzaOrder']['pizza_wave_id'];

		if (!$this->PizzaWave->isOrderable()) {
			throw new UnauthorizedException(__('It is not possible to delete pizza order anymore in this wave'));
		}

		if (!$this->isNotTreated()) {
			throw new UnauthorizedException(__('Pizza order already delivered'));
		}

		$dataSource = $this->getDataSource();
		$dataSource->begin();

		if ($this->delete() && $this->User->balanceIncrease($this->getItemsSum())) {
			$dataSource->commit();
			return true;
		} else {
			$dataSource->rollback();
			return false;
		}
	}

	public function isNotTreated() {
		$this->read(array('status'));

		return $this->data['PizzaOrder']['status'] == 0;
	}

}

?>
