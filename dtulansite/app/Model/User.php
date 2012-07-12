<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User'
 *
 * @author Nigrea
 */
class User extends AppModel {
    
    public $validate = array(
		'first_name' => array(
			'required' => array(
				'rule' => array('/[^0-9]', 'alphanumeric'), 
				'message' => 'Only letters are allowed in first name')
		),
		'last_name' => array(
			'required' => array(
				'rule' => array('/[^0-9]', 'alphanumeric'),
				'message' => 'Only letters are allowed in first name')
		),
		'email' => array(
			'required1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Email address is required'),
			'required2' => array(
				'rule' => array('email', true),
				'message' => 'Please supply a valid email address'),
			'required3' => array(
				'rule' => array('isUnique'),
				'message' => 'This Email address has already been taking'
			)
		),
		'type' => array(
            'valid' => array(
                'rule' => array('inList', array('g', 's')),
                'message' => 'Please enter a valid type',
                'allowEmpty' => false
            )
		),
		'id_number' => array(
			'valid' => array(
				'rule' => array('numeric'),
				'message' => 'Only digits are allowed for ID number'
			)
		),
		'password' => array(
			'rule' => array('minLenght', 8),
			'message' => 'Password must be at least 8 characters long'
		)
	);
	
	public $validationSets = array(
		'activate' => array(
			'first_name' => array(
			'required' => array(
				'rule' => array('/[^0-9]', 'alphanumeric'), 
				'message' => 'Only letters are allowed in first name')
			),
			'last_name' => array(
				'required' => array(
					'rule' => array('/[^0-9]', 'alphanumeric'),
					'message' => 'Only letters are allowed in first name')
			),
			'email' => array(
				'required1' => array(
					'rule' => array('notEmpty'),
					'message' => 'Email address is required'),
				'required2' => array(
					'rule' => array('email', true),
					'message' => 'Please supply a valid email address'),
				'required3' => array(
					'rule' => array('isUnique'),
					'message' => 'This Email address has already been taking'
				)
			),
			'type' => array(
				'valid' => array(
					'rule' => array('inList', array('g', 's')),
					'message' => 'Please enter a valid type',
					'allowEmpty' => false
				)
			),
			'id_number' => array(
				'valid' => array(
					'rule' => array('numeric'),
					'message' => 'Only digits are allowed for ID number'
				)
			),
			'password' => array(
				'rule' => array('minLenght', 8),
				'message' => 'Password must be at least 8 characters long'
			),
			'password_confim' => array(
				'rule' => array('confimPassword'), 
				'message' => 'Passwords do not match, please try again'
			)
		)
	);
	
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
			return true;
	}
}
?>
