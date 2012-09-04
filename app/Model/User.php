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
	public $hasMany = array('Payment', 'PizzaOrder', 'Crew', 'LanSignup', 'TeamInvite', 'TeamUser', 'LanInvite' => array(
			'className' => 'LanInvite',
			'foreignKey' => 'user_guest_id'
		), 'LanInviteSent' => array(
			'className' => 'LanInvite',
			'foreignKey' => 'user_guest_id'
		)
	);
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
			$this->data['User']['id_number'] = $this->getGuestNumber();
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
	}

	public function getGuestNumber() {
		$guestNumber = 'g' . date('ym');

		$count = $this->find('count', array(
			'conditions' => array(
				'User.id_number LIKE' => $guestNumber . '%'
			)
				)
		);
		if ($count >= 99) {
			throw new TooManyGuestSignUps();
		}
		return $count < 9 ? $guestNumber . '0' . ($count + 1) : $guestNumber . ($count + 1);
	}

        public function isActivated() {
			// Activated is an integer, where 0 means not activated
            return $this->data['User']['activated'];
        }
}

?>
