<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaCategory
 *
 * @author Jens
 */
class PizzaCategory extends AppModel {

	public $name = 'PizzaCategory';
	public $hasMany = array(
		'Pizza' => array(
			'className' => 'Pizza',
//			'conditions' => array('Pizza.available' => '1'),
//			'order' => 'Pizza.number ASC'
		),
		'PizzaCategoryType'
	);

}

?>
