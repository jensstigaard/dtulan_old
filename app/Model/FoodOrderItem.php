<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SweetOrderItem
 *
 * @author Jens
 */
class FoodOrderItem extends AppModel {
	public $belongsTo = array(
		'Food', 'FoodOrder'
	);
}

?>
