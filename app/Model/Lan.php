<?php

class Lan extends AppModel {

	public $name = 'Lan';
	public $hasMany = array(
		'LanSignup',
		'LanDay',
		'LanInvite',
		'Tournament',
		'PizzaWave',
	);
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
				'rule' => array('between', 0, 1),
				'message' => 'Valid publishing required'
			)
		),
		'sign_up_open' => array(
			'required' => array(
				'rule' => array('between', 0, 1),
				'message' => 'Valid Sign Up Open required'
			)
		),
		'time_start' => array(
			'bigger than end' => array(
				'rule' => 'validateDates',
				'message' => 'Invalid start-/end-time',
			)
		)
	);

	public function validateDates($check) {
		if ($check['time_start'] >= $this->data['Lan']['time_end']) {
			$this->invalidate('time_end', 'Invalid start-/end-time');
			return false;
		}
		return true;
	}

	public function getLanDays($start, $end) {
		App::uses('CakeTime', 'Utility');

		$date_start = $start['year'] . '-' . $start['month'] . '-' . $start['day'];
		$date_end = $end['year'] . '-' . $end['month'] . '-' . $end['day'];

		$days = array();

		$date_current = $date_start;
		while ($date_current <= $date_end) {
			$days[] = array('date' => $date_current);

			$date_current = CakeTime::format('Y-m-d', strtotime('+1 day', strtotime($date_current)));
		}

		return $days;
	}

	public function isOnAir($is_admin) {
		$currentTime = date('Y-m-d H:i:s');

		$conditions = array(
			'Lan.time_end >' => $currentTime,
			'Lan.time_start <' => $currentTime
		);

		if (!$is_admin) {
			$conditions['Lan.published'] = 1;
		}

		$count = $this->find('count', array(
			'conditions' => $conditions
				)
		);

		return $count;
	}

	public function getOnAir($is_admin) {
		$currentTime = date('Y-m-d H:i:s');

		$conditions = array(
			'Lan.time_end >' => $currentTime,
			'Lan.time_start <' => $currentTime
		);

		if (!$is_admin) {
			$conditions['Lan.published'] = 1;
		}

		$this->recursive = 0;

		$data = $this->find('first', array(
			'conditions' => $conditions
				)
		);

		return $data;
	}

	public function isCurrent($is_admin){
		$currentTime = date('Y-m-d H:i:s');

		$conditions = array(
			'Lan.time_end >' => $currentTime,
		);

		if (!$is_admin) {
			$conditions['Lan.published'] = 1;
		}

		$count = $this->find('count', array(
			'conditions' => $conditions
				)
		);

		return $count;
	}

	public function getCurrent($is_admin){
		$currentTime = date('Y-m-d H:i:s');

		$conditions = array(
			'Lan.time_end >' => $currentTime,
		);

		if (!$is_admin) {
			$conditions['Lan.published'] = 1;
		}

		$this->recursive = 0;

		$data = $this->find('first', array(
			'conditions' => $conditions
				)
		);

		return $data;
	}

	public function getInviteableUsers($lan_id, $user_id) {
		$this->id = $lan_id;

		if (!$this->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$this->recursive = 1;
		$lan = $this->read();

		$user_ids = array();
		foreach ($lan['LanSignup'] as $user) {
			$user_ids[] = $user['user_id'];
		}

		$count_invites = 0;
		foreach ($lan['LanInvite'] as $user) {
			$user_ids[] = $user['user_guest_id'];

			if ($user['user_student_id'] == $user_id) {
				$count_invites++;
			}
		}

		$users = array();

		// Only the max participants is it possible to invite
		if ($lan['Lan']['max_participants'] > count($user_ids) && $lan['Lan']['max_guests_per_student'] > $count_invites) {

			$users = $this->LanSignup->User->find('list', array('conditions' => array(
					'NOT' => array(
						'User.id' => $user_ids,
					),
					'User.type' => 'guest',
					'User.activated' => 1
				)
					)
			);

//			foreach ($users as $user) {
//				$users_list[$user['User']['id']] = $user['User']['name'];
//			}
		}

		return $users;
	}

	public function isUserAttending($lan_id, $user_id) {
		return $this->LanSignup->find('count', array('conditions' => array(
						'LanSignup.lan_id' => $lan_id,
						'LanSignup.user_id' => $user_id
					)
						)
				) == 1;
	}

}

?>
