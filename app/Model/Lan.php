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

	public function isOnAir() {
		$currentTime = date('Y-m-d H:i:s');

		$count = $this->find('count', array(
			'conditions' => array(
				'Lan.time_end >' => $currentTime,
				'Lan.time_start <' => $currentTime
			)
				)
		);

		return $count;
	}

	public function getOnAir() {
		$currentTime = date('Y-m-d H:i:s');

		$this->recursive = 0;

		$data = $this->find('first', array(
			'conditions' => array(
				'Lan.time_end >' => $currentTime,
				'Lan.time_start <' => $currentTime
			)
				)
		);

		return $data;
	}

	public function getInviteableUsers($id = null) {
		$this->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$lan = $this->read();

		$user_ids = array();
		foreach ($lan['LanSignup'] as $user) {
			$user_ids[] = $user['user_id'];
		}

		foreach ($lan['LanInvite'] as $user) {
			$user_ids[] = $user['user_guest_id'];
		}

		$users_list = array();

		// Only the max participants is it possible to invite
		if (count($user_ids) < $lan['Lan']['max_participants']) {

			$users = $this->LanSignup->User->find('all', array('conditions' => array(
					'NOT' => array(
						'User.id' => $user_ids,
					),
					'User.type' => 'guest',
					'User.activated' => 1
				)
					)
			);

			foreach ($users as $user) {
				$users_list[$user['User']['id']] = $user['User']['name'];
			}
		}

		return $users_list;
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
