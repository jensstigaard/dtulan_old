<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pizza
 *
 * @author Jens
 */
class Pizza extends AppModel {

	public $hasMany = 'PizzaPrices';
	public $belongsTo = array(
		'PizzaCategory' => array(
			'className' => 'PizzaCategory',
			'foreignKey' => 'category_id'
		)
	);
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		)
	);

}

?>
