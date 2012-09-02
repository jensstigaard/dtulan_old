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
			)
		)
	);

	public function isPizzaPriceId($check) {
		$this->PizzaPrice->id = $check['pizza_price_id'];

		if ($this->PizzaPrice->exists()) {
			return true;
		} else {
			return false;
		}
	}
}

?>
