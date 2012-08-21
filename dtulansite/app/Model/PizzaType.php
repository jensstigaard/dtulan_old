<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaType
 *
 * @author Jens
 */
class PizzaType extends AppModel {

	public $name = 'PizzaType';
	public $hasMany = array(
		'PizzaCategoryType'
	);

}

?>
