<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Jens
 */
App::uses('CakeEvent', 'Event');

class User extends AppModel {

	public $name = 'User';
	public $hasOne = array(
		 'Admin',
		 'UserPasswordTicket',
		 'QrCode'
	);
	public $hasMany = array(
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
		 'FoodOrder',
		 'TeamInvite',
		 'TeamUser',
	);
	public $helpers = array(
		 'Js'
	);
	public $order = array(
		 'name' => 'asc'
	);
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
					'message' => 'Email is required',
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
			  'balance low' => array(
					'rule' => 'validateBalanceLow',
					'message' => 'Your balance is going to be too low'
			  ),
			  'balance high' => array(
					'rule' => 'validateBalanceHigh',
					'message' => 'Your balance is going to be too high'
			  )
		 ),
		 'email_gravatar' => array(
			  'required2' => array(
					'rule' => array('email', true),
					'message' => 'Please supply a valid email address',
					'allowEmpty' => true,
			  ),
		 ),
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

	public function validatePhonenumber($check) {
		if (!empty($check['phonenumber']) && !preg_match("/^[0-9]{8}$/", $check['phonenumber'])) {
			return false;
		}

		return true;
	}

	public function validateName($check) {
		$name = explode(' ', $check['name']);
		if (count($name) > 1) {
			return true;
		}
		return false;
	}

	public function validateBalanceLow($check) {
		if ($check['balance'] >= -50) {
			return true;
		}
		return false;
	}

	public function validateBalanceHigh($check) {
		if ($check['balance'] <= 999) {
			return true;
		}
		return false;
	}

	public function getGuestNumber() {
		$guestNumber = 'g' . date('ym');

		$count = $this->find('first', array(
			 'conditions' => array(
				  'User.id_number LIKE' => $guestNumber . '%'
			 ),
			 'order' => array(
				  'id_number' => 'DESC'
			 )
				  )
		);
		if (!isset($count['User'])) {
			$count = 0;
		} else {
			$count = intval(substr($count['User']['id_number'], 5));
			if ($count >= 99) {
				throw new TooManyGuestSignUps();
			}
		}

		return $count < 9 ? $guestNumber . '0' . ($count + 1) : $guestNumber . ($count + 1);
	}

	public function getUserIDsNotAdmin() {
		return $this->find('list', array(
						'conditions' => array(
							 'User.activated' => 1,
							 'NOT' => array(
								  'User.id' => $this->Admin->getUserIDsAdmins()
							 )
						)
							 )
		);
	}

	public function isActivated() {
		return isset($this->data['User']['activated']) && $this->data['User']['activated'];
	}

	public function isStudent() {
		$this->read(array('type'));

		return $this->data['User']['type'] === 'student';
	}

	public function isAdmin() {
		$this->read(array('Admin.user_id'));
		return isset($this->data['Admin']['user_id']);
	}

	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (isset($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		if (isset($this->data['User']['name'])) {
			$this->data['User']['name'] = ucwords(strtolower($this->data['User']['name']));
		}
		// Creating user
		if (isset($this->data['User']['email'])) {
			$this->data['User']['email'] = strtolower($this->data['User']['email']);
			$this->data['User']['email_gravatar'] = $this->data['User']['email'];
			$this->data['User']['time_created'] = date('Y-m-d H:i:s');
		}
		return true;
	}

	public function getNewLans() {
		$lans = $this->LanSignup->find('all', array(
			 'conditions' => array(
				  'user_id' => $this->id
			 ),
			 'recursive' => 0
				  )
		);

		$lan_ids = array();
		foreach ($lans as $lan) {
			$lan_ids[] = $lan['LanSignup']['lan_id'];
		}


		return $this->LanSignup->Lan->find('first', array(
						'conditions' => array(
							 'Lan.sign_up_open' => 1,
							 'Lan.published' => 1,
							 'Lan.time_end >' => date('Y-m-d H:i:s'),
							 'NOT' => array(
								  'Lan.id' => $lan_ids
							 )
						),
						'order' => array('Lan.time_start ASC'),
						'recursive' => 0
							 )
		);
	}

	public function getTournamentTeamInvites() {

		return $this->TeamInvite->find('all', array(
						'conditions' => array(
							 'user_id' => $this->id
						),
						'recursive' => 0
							 )
		);
	}

	public function isCrewForUser($user_id_crew) {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT COUNT(Crew.id) AS CrewCount FROM `crews` AS Crew INNER JOIN `lans` AS Lan ON Crew.lan_id = Lan.id INNER JOIN `lan_signups` AS LanSignup ON Lan.id = LanSignup.lan_id WHERE LanSignup.user_id = ? AND Crew.`user_id` = ?", array($this->id, $user_id_crew));
		return $total[0][0]['CrewCount'] > 0;
	}

	public function getNewestCrewId($user_id_crew) {
		$db = $this->getDataSource();
		$total = $db->fetchAll("(SELECT Crew.id AS CrewId, Lan.title AS LanTitle, Lan.time_start AS time_start FROM `crews` AS Crew INNER JOIN `lans` AS Lan ON Crew.lan_id = Lan.id INNER JOIN `lan_signups` AS LanSignup ON Lan.id = LanSignup.lan_id WHERE LanSignup.user_id = ? AND Crew.`user_id` = ?) UNION ALL (SELECT Crew1.id AS CrewId, Lan.title AS LanTitle, Lan.time_start AS time_start FROM `crews` AS Crew1 INNER JOIN `lans` AS Lan ON Crew1.lan_id = Lan.id INNER JOIN `crews` AS Crew2 ON Lan.id = Crew2.lan_id WHERE Crew1.user_id = ? AND Crew2.`user_id` = ?) ORDER BY time_start DESC LIMIT 1", array($this->id, $user_id_crew, $this->id, $user_id_crew));
		return $total[0][0];
	}

	public function balanceIncrease($amount) {
		$this->read(array('balance'));

		$new_balance = $this->data['User']['balance'] + $amount;

		return $this->saveField('balance', $new_balance, true);
	}

	public function balanceDecrease($amount) {

		$this->read(array('balance'));

		$new_balance = $this->data['User']['balance'] - $amount;

		return $this->saveField('balance', $new_balance, true);
	}

	public function createUser($input) {
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		$this->create();
		if ($this->save($input)) {

			$input = $input + array(
				 'id' => $this->getLastInsertID()
			);

			$event = new CakeEvent('Model.User.activationEmail', $this, array(
							'user' => $input
					  ));
			$this->getEventManager()->dispatch($event);

			if ($event->result['success']) {
				$dataSource->commit();
				return true;
			}
		}

		debug($event->result);
		$dataSource->rollback();
		return false;
	}

	public function getSubscribingUsersNameAndEmail() {
		return $this->find('all', array(
						'conditions' => array(
							 'email_subscription' => true
						),
						'fields' => array(
							 'User.name',
							 'User.email'
						),
						'recursive' => -1
				  ));
	}

	public function getDataToEditPage() {

		return $this->find('first', array(
						'conditions' => array(
							 'id' => $this->id
						),
						'recursive' => -1,
						'fields' => array(
							 'id',
							 'name',
							 'phonenumber',
							 'email_gravatar',
							 'email_subscription',
							 'gamertag',
						)
				  ));
	}

	public function getTeams() {
		$teams = $this->TeamUser->find('all', array(
			 'conditions' => array(
				  'TeamUser.user_id' => $this->id
			 ),
			 'fields' => array(
				  'team_id',
				  'is_leader'
			 ),
			 'contain' => array(
				  'Team' => array(
						'fields' => array(
							 'id',
							 'name',
							 'tournament_id',
						),
						'Tournament' => array(
							 'fields' => array(
								  'title',
								  'slug',
								  'team_size',
							 ),
							 'Lan' => array(
								  'slug',
								  'title'
							 ),
							 'Game' => array(
								  'Image' => array(
										'fields' => array(
											 'id',
											 'ext'
										)
								  )
							 )
						)
				  )
			 )
				  )
		);

		foreach ($teams as $key => $team) {
			$this->TeamUser->Team->id = $team['Team']['id'];
			$teams[$key]['Team']['count'] = $this->TeamUser->Team->countMembers();
			$teams[$key]['Team']['Tournament']['Game']['Image']['filePath'] = $this->TeamUser->Team->Tournament->Game->Image->getFileName($team['Team']['Tournament']['Game']['Image']);
		}

		return $teams;
	}

}

?>
