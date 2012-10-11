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
		'Pizza'
	);
	public $hasAndBelongsToMany = array(
		'PizzaType' => array(
			'joinTable' => 'pizza_category_types'
		)
	);

	public $order = array(
		'sorted' => 'asc'
	);

	public $validate = array(
		'title' => array(
			'not empty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title cannot be empty'
			)
		)
	);

}

?>
