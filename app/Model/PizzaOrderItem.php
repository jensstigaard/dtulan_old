<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaOrderItem
 *
 * @author Jens
 */
class PizzaOrderItem extends AppModel {

	public $belongsTo = array('PizzaOrder', 'PizzaPrice');
	public $validate = array(
		'pizza_price_id' => array(
			'exists' => array(
				'rule' => 'isPizzaPriceId',
				'message' => 'Invalid pizza or pizza type'
			),
			'validate Pizza' => array(
				'rule' => 'validatePizzaWave',
				'message' => 'Invalid pizza wave'
			)
		),
	);

	public function validatePizzaPriceId($check) {
		$this->PizzaPrice->id = $check['pizza_price_id'];

		return $this->PizzaPrice->exists();
	}
	
	public function validatePizzaWave($check){
		
		return 0;
	}

}

?>
