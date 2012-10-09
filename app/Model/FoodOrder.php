<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SweetOrder
 *
 * @author Jens
 */
class FoodOrder extends AppModel {
	public $belongsTo = array(
		'User'
	);

	public $hasMany = array(
		'FoodOrderItem'
	);
}

?>
