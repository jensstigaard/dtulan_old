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
	public $hasAndBelongsToMany = array(
		'PizzaCategory' => array(
			'joinTable' => 'pizza_category_types'
		)
	);
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty', 'alphaNumeric'),
				'message' => 'Title cannot be empty'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'A type with given title already exist'
			)
		)
	);

}

?>
