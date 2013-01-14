<?php

class FoodCategory extends AppModel {

	public $belongsTo = array(
		'FoodMenu'
	);
	public $hasMany = array(
		'Food'
	);
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Title cannot be empty'
			),
		),
	);

}

?>
