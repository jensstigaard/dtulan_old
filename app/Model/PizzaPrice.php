<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaPrices
 *
 * @author Jens
 */
class PizzaPrice extends AppModel {

	public $name = 'PizzaPrice';
	public $belongsTo = array('Pizza', 'PizzaType');
	public $hasMany = array('PizzaOrderItem');

	public $validate = array(
//		'price' => array(
//			'required' => array(
//				'rule' => '/^[0-9]$/',
//				'message' => 'Invalid price'
//			)
//		)
	);
}

?>
