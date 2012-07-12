<?php

class Lan extends AppModel {
	
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		),
		'max_participants' => array(
			'required' => array(
				'rule' => array('numeric'),
				'message' => 'Has to be a number'
			)
		),
		'max_guests_per_student' => array(
			'required' => array(
				'rule' => array('numeric'),
				'message' => 'Has to be a number'
			)
		),
		'published' => array(
			'required' => array(
				'rule' => array('between',0,1),
				'message' => 'Valid publishing required'
			)
		),
		'sign_up_open' => array(
			'required' => array(
				'rule' => array('between',0,1),
				'message' => 'Valid Sign Up Open required'
			)
		)
		
		);
	
	
}

?>
