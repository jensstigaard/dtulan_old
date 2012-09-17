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
	public $hasOne = array('Admin', 'UserPasswordTicket');
	public $hasMany = array(
		'Crew',
		'LanSignup',
		'LanInvite' => array(
			'className' => 'LanInvite',
			'foreignKey' => 'user_guest_id'
		),
		'LanInviteSent' => array(
			'className' => 'LanInvite',
			'foreignKey' => 'user_guest_id'
		),
		'Payment',
		'PizzaOrder',
		'TeamInvite',
		'TeamUser',
	);
	public $helpers = array('Js');
	public $validate = array(
		'name' => array(
			'required1' => array(
				'rule' => array('notEmpty'),
				'message' => 'Full name is required'
			),
                        'required2' => array(
				'rule' => array('validateName'),
				'message' => 'Full name is required'
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
		'phonenumber' => array(
			'validPhone' => array(
				'rule' => 'validatePhonenumber',
				'message' => 'Please enter a valid phonenumber',
				'allowEmpty' => true

			)
		),
		'gamertag' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 20),
				'message' => 'Too long gamertag entered',
				'allowEmpty' => true
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
		),
		'balance' => array(
			'balance' => array(
				'rule' => array('between', -100, 999),
				'message' => 'Your balance is too low'
			)
		)
	);

	public function validateStudynumber($check) {
          	if ($this->data['User']['type'] == 'student') {
                    $this->data['User']['id_number'] = strtolower($this->data['User']['id_number']);
                    return preg_match("/^s[0-9]{6}$/", $this->data['User']['id_number']);
		} else {
                    $this->data['User']['id_number'] = $this->getGuestNumber();
                    return true;
		}
	}

	public function matchPasswords($check) {
		if (isset($this->data['User']['password_confirmation']) && $check['password'] == $this->data['User']['password_confirmation']) {
			return true;
		}
		$this->invalidate('password_confirmation', 'Your passwords do not match');
		return false;
	}

	public function validatePhonenumber($check){
		if(!empty($check['phonenumber']) && !preg_match("/^[0-9]{8}$/", $check['phonenumber'])){
			return false;
		}

		return true;
	}

        public function validateName($check) {
            $name = explode(' ', $check['name']);
            if(count($name) > 1) {
                return true;
            }
            return false;
        }
        
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (isset($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
                if(isset($this->data['User']['name'])) {
                    $this->data['User']['name'] = ucwords(strtolower($this->data['User']['name']));
                }
                if(isset($this->data['User']['email'])) {
                    $this->data['User']['email'] = strtolower($this->data['User']['email']);
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
		return isset($this->data['User']['activated']) && $this->data['User']['activated'];
	}

	public function isAdmin($user = null){
		return isset($user['Admin']['user_id']);
	}

	public function getName() {
		return $this->data['User']['name'];
	}

	public function getEmail() {
		return $this->data['User']['email'];
	}
}

?>
