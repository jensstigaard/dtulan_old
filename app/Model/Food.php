<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SweetModel
 *
 * @author Jens
 */
class Food extends AppModel {

	public $belongsTo = array(
		'FoodCategory'
	);

	public $hasMany = array(
		'FoodOrderItem'
	);

	public $order = array(
		'available' => 'desc',
		'sorted' => 'asc'
	);
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Title cannot be empty'
			),

		),
		'price' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Price cannot be empty'
			),
			'price' => array(
				'rule' => '/^[0-9]{1,}$/i',
				'message' => 'Invalid price'
			)
		)
	);

	public function countTimesSold(){
		return $this->FoodOrderItem->find('count', array(
			'conditions' => array(
				'food_id' => $this->id
			)
		));
	}

}

?>
