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

	public $name = 'User';
	public $hasOne = array('Admin');
	public $hasMany = array('Payment', 'Order', 'Crew', 'LanSignup');
	public $helpers = array('Js');
	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'First name is required'
			)
		),
		'email' => array(
			'required1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Email name is required',
			),
			'required2' => array(
				'rule' => array('email', true),
				'message' => 'Please supply a valid email address'
			),
			'required3' => array(
				'rule' => 'isUnique',
				'message' => 'This email has already been taken'
			)
		),
		'type' => array(
			'valid' => array(
				'rule' => array('inList', array('guest', 'student')),
				'message' => 'Please enter a valid type',
				'allowEmpty' => false
			)
		),
		'id_number' => array(
			'required' => array(
				'rule' => 'validateStudynumber',
				'message' => 'Not a valid study number'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'This studynumber is already in use'
			)
		),
		'password' => array(
			'Not empty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your password'
			),
			'Length' => array(
				'rule' => array('minLength', 8),
				'message' => 'Password must be at least 8 characters long'
			),
			'Match passwords' => array(
				'rule' => 'matchPasswords',
				'message' => 'Your passwords do not match'
			)
		),
		'password_confirmation' => array(
			'Not empty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please confirm your password'
			),
		)
	);

	public function validateStudynumber($check) {

		if ($this->data['User']['type'] == 'student') {
			//$this->data['User']['id_number'] = str_replace('s', '', $this->data['User']['id_number']);
			return preg_match("/^s[0-9]{6}$/", $this->data['User']['id_number']);
		} else {
			$this->data['User']['id_number'] = '0';
			return true;
		}
	}

	public function matchPasswords($check) {
		if ($check['password'] == $this->data['User']['password_confirmation']) {
			return true;
		}
		$this->invalidate('password_confirmation', 'Your passwords do not match');
		return false;
	}

	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (isset($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		return true;
//		$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
//		return true;
	}

//	public $validate = array(
//		'name' => array(
//			'required' => array(
//				'rule' => array('/[^0-9]/', 'alphanumeric'),
//				'message' => 'Only letters are allowed in first name')
//		),
//		'email' => array(
//			'required1' => array(
//				'rule' => array('notEmpty'),
//				'message' => 'Email address is required'),
//			'required2' => array(
//				'rule' => array('email', true),
//				'message' => 'Please supply a valid email address'),
//			'required3' => array(
//				'rule' => array('isUnique'),
//				'message' => 'This Email address has already been taking'
//			)
//		),
//		'type' => array(
//			'valid' => array(
//				'rule' => array('inList', array('guest', 'student')),
//				'message' => 'Please enter a valid type',
//				'allowEmpty' => false
//			)
//		),
//		'id_number' => array(
//			'valid' => array(
//				'rule' => array('numeric'),
//				'message' => 'Only digits are allowed for ID number'
//			)
//		),
//		'password' => array(
//			'rule' => array('minLenght', 8),
//			'message' => 'Password must be at least 8 characters long'
//		)
//	);
//	public $validationSets = array(
//		'activate' => array(
//			'name' => array(
//				'required' => array(
//					'rule' => array('/[^0-9]', 'alphanumeric'),
//					'message' => 'Only letters are allowed in first name')
//			),
//			'email' => array(
//				'required1' => array(
//					'rule' => array('notEmpty'),
//					'message' => 'Email address is required'),
//				'required2' => array(
//					'rule' => array('email', true),
//					'message' => 'Please supply a valid email address'),
//				'required3' => array(
//					'rule' => array('isUnique'),
//					'message' => 'This Email address has already been taking'
//				)
//			),
//			'type' => array(
//				'valid' => array(
//					'rule' => array('inList', array('g', 's')),
//					'message' => 'Please enter a valid type',
//					'allowEmpty' => false
//				)
//			),
//			'id_number' => array(
//				'valid' => array(
//					'rule' => array('numeric'),
//					'message' => 'Only digits are allowed for ID number'
//				)
//			),
//			'password' => array(
//				'rule' => array('minLenght', 8),
//				'message' => 'Password must be at least 8 characters long'
//			),
//			'password_confim' => array(
//				'rule' => array('confimPassword'),
//				'message' => 'Passwords do not match, please try again'
//			)
//		)
//	);
}

?>
