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
}

?>
