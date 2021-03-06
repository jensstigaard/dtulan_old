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

}

?>
