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
class PizzaOrderItem {
	public $belongsTo = array('PizzaOrder', 'PizzaPrice');
}

?>
