<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaTypeCategory
 *
 * @author Jens
 */
class PizzaCategoryType extends AppModel {

	public $name = 'PizzaCategoryType';
	public $hasOne = array(
		'PizzaCategory', 'PizzaType'
	);

}

?>
