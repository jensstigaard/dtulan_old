<?php

class Lan extends AppModel {

	public $name = 'Lan';
	public $hasMany = array(
		'Crew',
		'LanSignup',
		'LanDay',
		'LanInvite',
		'Tournament',
		'PizzaWave',
		'FoodOrder'
	);
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		),
		'slug' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Lan has to have an unique title'
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

	public function countSignups() {
		return $this->LanSignup->find('count', array(
					'conditions' => array(
						'LanSignup.lan_id' => $this->id
					)
						)
		);
	}

	public function countGuests() {
		return $this->LanInvite->find('count', array(
					'conditions' => array(
						'LanInvite.lan_id' => $this->id,
						'LanInvite.accepted' => 1
					)
						)
		);
	}

	public function countTournaments() {
		return $this->Tournament->find('count', array(
					'conditions' => array(
						'Tournament.lan_id' => $this->id
					)
						)
		);
	}

	public function getLanDaysByTime($start, $end) {
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

	public function isCurrent($is_admin) {
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

	public function getCurrent($is_admin) {
		$currentTime = date('Y-m-d H:i:s');

		$conditions = array(
			'Lan.time_end >' => $currentTime,
		);

		if (!$is_admin) {
			$conditions['Lan.published'] = 1;
		}

		$data = $this->find('first', array(
			'conditions' => $conditions,
			'order' => array(
				'time_end ASC'
			),
			'recursive' => 1
				)
		);

		return $data;
	}

	public function getInviteableUsers($user_id) {
		$this->recursive = 1;
		$lan = $this->read();

		$user_ids = array();
		foreach ($lan['LanSignup'] as $user) {
			$user_ids[] = $user['user_id'];
		}

//		$user_ids_signed_up = $user_ids;

		$count_invites = 0;
		foreach ($lan['LanInvite'] as $user) {
			$user_ids[] = $user['user_guest_id'];

			if ($user['user_student_id'] == $user_id) {
				$count_invites++;
			}
		}

		$users = array();

		// Only the max participants is it possible to invite
		// $lan['Lan']['max_participants'] > count($user_ids)
		if ($this->isSignupPossible($this->id) && $lan['Lan']['max_guests_per_student'] > $count_invites) {

			$users = $this->LanSignup->User->find('list', array('conditions' => array(
					'NOT' => array(
						'User.id' => $user_ids,
					),
					'User.type' => 'guest',
					'User.activated' => 1
				)
					)
			);
		}

		return $users;
	}

	public function isPublished($lan_id, $is_admin = false) {
		$this->id = $lan_id;

		if (!$this->exists()) {
			throw new NotFoundException('Lan not found');
		}

		if ($is_admin) {
			return true;
		}

		$this->read(array('published'));

		return $this->data['Lan']['published'];
	}

	public function isSignupPossible($lan_id) {
		if ($this->isPublished($lan_id)) {
			$max_participants = $this->read(array('max_participants'));

			$this->LanDay->recursive = 0;
			$lan_days = $this->LanDay->find('all', array('conditions' => array('LanDay.lan_id' => $lan_id)));

			foreach ($lan_days as $day) {
				if ($this->LanDay->seatsLeft($day['LanDay']['id'])) {
					return true;
				}
			}
		}

		return false;
	}

	public function isUserAttending($lan_id, $user_id) {
		return $this->LanSignup->find('count', array('conditions' => array(
						'LanSignup.lan_id' => $lan_id,
						'LanSignup.user_id' => $user_id
					)
						)
				) == 1;
	}

	public function getFoodOrderIds() {
		$food_orders = $this->FoodOrder->find('all', array(
			'conditions' => array(
				'lan_id' => $this->id
			),
			'fields' => array(
				'id'
			)
				)
		);

		$food_order_ids = array();
		foreach ($food_orders as $food_order) {
			$food_order_ids[] = $food_order['FoodOrder']['id'];
		}

		return $food_order_ids;
	}

	public function getCountFoodOrders() {
		return $this->FoodOrder->find('count', array(
					'conditions' => array(
						'lan_id' => $this->id
					)
						)
		);
	}

	public function getFoodOrdersTotal() {
		$sum = $this->FoodOrder->FoodOrderItem->find('all', array(
			'fields' => array(
				'sum(FoodOrderItem.quantity * FoodOrderItem.price) AS ctotal'
			),
			'conditions' => array(
				'FoodOrderItem.food_order_id' => $this->getFoodOrderIds()
			)
				)
		);
		return $sum[0][0]['ctotal'];
	}

	public function getLanDays() {
		return $this->LanDay->find('all', array(
					'conditions' => array(
						'LanDay.lan_id' => $this->id
					),
					'order' => array(
						'LanDay.date ASC',
					)
						)
		);
	}

	public function countInvites() {
		return $this->LanInvite->find('count', array(
					'conditions' => array(
						'LanInvite.lan_id' => $this->id,
						'LanInvite.accepted' => 0
					),
						)
		);
	}

	public function getInvites() {
		return $this->LanInvite->find('all', array(
					'conditions' => array(
						'LanInvite.lan_id' => $this->id,
						'LanInvite.accepted' => 0
					),
					'recursive' => 2
						)
		);
	}

	public function getCrew() {
		return $this->Crew->find('all', array('conditions' => array(
						'Crew.lan_id' => $this->id
					),
					'fields' => array(
						'Crew.user_id'
					),
						)
		);
	}

}

?>
