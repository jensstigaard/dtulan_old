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
		 'QrCode',
		 'UserPasswordTicket',
	);
	public $hasMany = array(
		 'Crew',
		 'FoodOrder',
		 'LanSignup',
		 'Payment',
		 'PizzaOrder',
		 'TeamInvite',
		 'TeamUser',
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

	public function beforeValidate($options = array()) {
		parent::beforeValidate($options);

		if (isset($this->data['User']['name'])) {
			$this->data['User']['name'] = ucwords(strtolower($this->data['User']['name']));
		}
		// Creating user
		if (isset($this->data['User']['email'])) {
			$this->data['User']['email'] = strtolower($this->data['User']['email']);
			$this->data['User']['email_gravatar'] = $this->data['User']['email'];
			$this->data['User']['time_created'] = date('Y-m-d H:i:s');
		}
	}

	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (isset($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		return true;
	}

	public function getNewLans() {
		return $this->LanSignup->Lan->find('first', array(
						'conditions' => array(
							 'Lan.sign_up_open' => 1,
							 'Lan.published' => 1,
							 'Lan.time_end >' => date('Y-m-d H:i:s'),
							 'NOT' => array(
								  'Lan.id' => $this->getLanIds()
							 )
						),
						'order' => array('Lan.time_start ASC'),
						'recursive' => 0,
						'fields' => array(
							 'slug',
							 'title',
							 'time_start',
							 'time_end',
						)
							 )
		);
	}

	public function getLanIds() {

		$lans_as_participant = $this->LanSignup->find('all', array(
			 'conditions' => array(
				  'user_id' => $this->id
			 ),
			 'fields' => array(
				  'lan_id'
			 )
				  )
		);

		$lans_as_crew = $this->Crew->find('all', array(
			 'conditions' => array(
				  'user_id' => $this->id
			 ),
			 'fields' => array(
				  'lan_id'
			 )
				  )
		);

		$lan_ids = array();

		foreach ($lans_as_participant as $lan) {
			$lan_ids[] = $lan['LanSignup']['lan_id'];
		}

		foreach ($lans_as_crew as $lan) {
			$lan_ids[] = $lan['Crew']['lan_id'];
		}



		return $lan_ids;
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
//		$total = $db->fetchAll("SELECT COUNT(Crew.id) AS CrewCount FROM `crews` AS Crew INNER JOIN `lans` AS Lan ON Crew.lan_id = Lan.id INNER JOIN `lan_signups` AS LanSignup ON Lan.id = LanSignup.lan_id WHERE LanSignup.user_id = ? AND Crew.`user_id` = ?", array($this->id, $user_id_crew));

		$total = $db->fetchAll("
			
			SELECT COUNT(Crew.id) AS CrewCount 
				FROM `crews` AS Crew 
					INNER JOIN `lans` AS Lan 
						ON Crew.lan_id = Lan.id
							INNER JOIN `lan_signups` AS LanSignup
								ON Lan.id = LanSignup.lan_id 
				WHERE LanSignup.user_id = ? AND Crew.`user_id` = ?
				
			UNION ALL
			
			SELECT COUNT(Crew.id) AS CrewCount
				FROM `crews` AS Crew
					INNER JOIN `lans` AS Lan
						ON Crew.lan_id = Lan.id
							INNER JOIN `crews` AS Crew2
							ON Lan.id = Crew2.lan_id
				WHERE Crew2.user_id = ? AND Crew.user_id = ?
			", array($this->id, $user_id_crew, $this->id, $user_id_crew));

//		debug($total);

		return $total[0][0]['CrewCount'] > 0 || $total[1][0]['CrewCount'] > 0;
	}

	public function getNewestCrewId($user_id_crew) {
		$db = $this->getDataSource();
		//$total = $db->fetchAll("(SELECT Crew.id AS CrewId, Lan.title AS LanTitle, Lan.time_start AS time_start FROM `crews` AS Crew INNER JOIN `lans` AS Lan ON Crew.lan_id = Lan.id INNER JOIN `lan_signups` AS LanSignup ON Lan.id = LanSignup.lan_id WHERE LanSignup.user_id = ? AND Crew.`user_id` = ?) UNION ALL (SELECT Crew1.id AS CrewId, Lan.title AS LanTitle, Lan.time_start AS time_start FROM `crews` AS Crew1 INNER JOIN `lans` AS Lan ON Crew1.lan_id = Lan.id INNER JOIN `crews` AS Crew2 ON Lan.id = Crew2.lan_id WHERE Crew1.user_id = ? AND Crew2.`user_id` = ?) ORDER BY time_start DESC LIMIT 1", array($this->id, $user_id_crew, $this->id, $user_id_crew));

		$total = $db->fetchAll("
			(
			SELECT Crew.id AS CrewId, Lan.title AS LanTitle, Lan.time_start AS time_start
				FROM `crews` AS Crew
					INNER JOIN `lans` AS Lan
						ON Crew.lan_id = Lan.id
							INNER JOIN `lan_signups` AS LanSignup
								ON Lan.id = LanSignup.lan_id
				WHERE LanSignup.user_id = ? AND Crew.`user_id` = ?
			)
			UNION ALL (
			SELECT Crew1.id AS CrewId, Lan.title AS LanTitle, Lan.time_start AS time_start
				FROM `crews` AS Crew1
					INNER JOIN `lans` AS Lan
						ON Crew1.lan_id = Lan.id
							INNER JOIN `crews` AS Crew2
								ON Lan.id = Crew2.lan_id
				WHERE Crew1.user_id = ? AND Crew2.`user_id` = ?
				) ORDER BY time_start DESC LIMIT 1", array($this->id, $user_id_crew, $this->id, $user_id_crew));

		return $total[0][0];
	}

	public function balanceIncrease($amount) {

		if (!$this->exists()) {
			throw new NotFoundException('User not found');
		}

		$this->read(array('balance'));

		$new_balance = $this->data['User']['balance'] + $amount;

		return $this->saveField('balance', $new_balance, true);
	}

	public function balanceDecrease($amount) {

		if (!$this->exists()) {
			throw new NotFoundException('User not found');
		}

		$this->read(array('balance'));

		$new_balance = $this->data['User']['balance'] - $amount;

		return $this->saveField('balance', $new_balance, true);
	}

	public function createUser($input) {
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		$this->create();
		if ($this->save($input)) {

			$user = $input['User'];

			$user['id'] = $this->getLastInsertID();

			$event = new CakeEvent('Model.User.activationEmail', $this, array(
							'user' => $user
					  ));
			$this->getEventManager()->dispatch($event);

			if ($event->result['success']) {
				$dataSource->commit();
				return true;
			}
		}

//		debug($event->result);
		$dataSource->rollback();
		return false;
	}

	public function createForgotPassword() {
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		$data['UserPasswordTicket']['user_id'] = $this->id;
		$data['UserPasswordTicket']['time'] = date('Y-m-d H:i:s');

		$this->UserPasswordTicket->deleteAll(array('UserPasswordTicket.user_id' => $this->id));

		if ($this->UserPasswordTicket->save($data)) {
			$user = $this->read(array('name', 'email'));

			$event = new CakeEvent('Model.User.forgotPasswordEmail', $this, array(
							'Ticket' => array(
								 'id' => $this->UserPasswordTicket->id,
							),
							'User' => $user['User']
					  ));
			$this->getEventManager()->dispatch($event);

			if ($event->result['success']) {
				$dataSource->commit();
				return true;
			}
		}

		$dataSource->rollback();
		return false;
	}

	public function getSubscribingUsersNameAndEmail($all_users = 0) {

		if (!$all_users) {
			$conditions = array(
				 'email_subscription' => true
			);
		}

		return $this->find('all', array(
						'conditions' => array(
							 $conditions
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
				  'TeamUser.user_id' => $this->id,
			 ),
			 'fields' => array(
				  'team_id',
				  'is_leader'
			 ),
			 'order' => array(
				  'Team.tournament_id' => 'desc'
			 ),
			 'contain' => array(
				  'Team' => array(
						'fields' => array(
							 'id',
							 'name',
							 'tournament_id',
						),
						'TournamentWinner' => array(
							 'fields' => array(
								  'place'
							 )
						),
						'Tournament' => array(
							 'fields' => array(
								  'title',
								  'slug',
								  'team_size',
							 ),
							 'Lan' => array(
								  'slug',
								  'title',
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
			$teams[$key]['Team']['Tournament']['Game']['Image']['thumbPath'] = $this->TeamUser->Team->Tournament->Game->Image->getThumbPath($team['Team']['Tournament']['Game']['Image']);
		}

		return $teams;
	}

	public function getCountTournamentsWon() {

		$tournaments_won = array();
		$count_total = 0;

		for ($i = 1; $i < 4; $i++) {
			$count_total += $tournaments_won[$i] = $this->getCountTournamentsWonAtPlace($i);
		}

		$tournaments_won['all'] = $count_total;

		return $tournaments_won;
	}

	public function getCountTournamentsWonAtPlace($place) {

		$db = $this->getDataSource();

		$total = $db->fetchAll("		
			SELECT COUNT(TeamUser.id) AS TeamUser
				FROM `team_users` AS TeamUser
					INNER JOIN `teams` AS Team
						ON TeamUser.team_id = Team.id
							INNER JOIN `tournament_winners` AS TournamentWinner
								ON Team.id = TournamentWinner.team_id
				WHERE TeamUser.user_id = ? AND TournamentWinner.place = ?
			", array($this->id, $place));

		return $total[0][0]['TeamUser'];
	}

	public function getStatisticsTimeCreation() {
		$db = $this->getDataSource();

		$total = $db->fetchAll("
			SELECT 
				COUNT(id) AS count,
				CONCAT(YEAR(time_created), '-', MONTH(time_created)) AS dato
			FROM users
			GROUP BY
				YEAR(time_created),
				MONTH(time_created);
			", array());


		$return = array();
		foreach ($total as $line) {

			$return[] = $line[0];
		}

		return $return;
	}

	public function getUserIdsByLike($string) {

		$users = $this->find('list', array(
			 'recursive' => -1,
			 'fields' => array(
				  'id',
			 ),
			 'conditions' => array(
				  'OR' => array(
						array(
							 'name LIKE' => '%' . $string . '%'
						),
						array(
							 'email LIKE' => '%' . $string . '%'
						),
						array(
							 'id_number LIKE' => '%' . $string . '%'
						),
				  )
			 ),
			 'limit' => 5
				  )
		);

		return $users;
	}

}

?>
