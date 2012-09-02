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

	public $hasMany = array('PizzaOrderItem');
	public $belongsTo = array('User', 'PizzaWave');
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
				'PizzaWave.time_end >' => date('Y-m-d H:i:s')
			)
				)
		);

		if ($count == 1) {
			return true;
		} else {
			return false;
		}
	}

}
?>
