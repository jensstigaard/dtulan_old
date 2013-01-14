<?php

class Lan extends AppModel {

	public $hasMany = array(
		'Crew' => array(
			'dependent' => true
		),
		'LanSignup' => array(
			'dependent' => true
		),
		'LanDay' => array(
			'dependent' => true
		),
		'LanInvite' => array(
			'dependent' => true
		),
		'Tournament' => array(
			'dependent' => true
		),
		'LanPizzaMenu' => array(
			'dependent' => true
		),
		'LanFoodMenu' => array(
			'dependent' => true
		),
		'LanSignupCode' => array(
			'dependent' => true
		)
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
				'message' => 'Valid "sign up open" required'
			)
		),
		'need_physical_code' => array(
			'required' => array(
				'rule' => array('between', 0, 1),
				'message' => 'Valid type required'
			)
		),
		'time_start' => array(
			'bigger than end' => array(
				'rule' => 'validateDates',
				'message' => 'Invalid start- or end-time',
			)
		)
	);

	public function getIdBySlug($slug) {
		$result = $this->find('first', array(
			'conditions' => array(
				'slug' => $slug
			),
			'fields' => array(
				'id'
			)
				));

		return $result['Lan']['id'];
	}

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

	public function countInvites() {
		return $this->LanInvite->find('count', array(
					'conditions' => array(
						'LanInvite.lan_id' => $this->id,
						'LanInvite.accepted' => 0
					),
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

	public function getHighlighted() {
		return $this->find('all', array(
					'conditions' => array(
						'Lan.highlighted' => 1
					),
					'recursive' => 1,
					'order' => array(
						'Lan.time_start' => 'asc'
					)
				));
	}

	// REFACTOR THIS METHOD
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

	public function isPublished() {

		if (!$this->exists()) {
			throw new NotFoundException('Lan not found');
		}

		if ($this->isYouAdmin()) {
			return true;
		}

		$this->read(array('published'));

		return $this->data['Lan']['published'];
	}

	public function isSignupOpen() {

		if (!$this->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$this->read(array('sign_up_open'));

		return $this->data['Lan']['sign_up_open'];
	}

	public function isPast() {

		$this->read(array('time_end'));

		return $this->data['Lan']['time_end'] < date('Y-m-d H:i:s');
	}

	public function isSignupPossible() {
		if ($this->isPublished() && $this->isSignupOpen()) {
			return true;
		}

		return false;
	}

	public function isUserAbleSignup() {

		if ($this->LanSignup->User->isStudent() && !$this->isUserAttending()) {
			return true;
		}

		return false;
	}

	public function isUserAttending() {
		return $this->LanSignup->find('count', array('conditions' => array(
						'LanSignup.lan_id' => $this->id,
						'LanSignup.user_id' => $this->LanSignup->User->id
					)
						)
				) == 1;
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

	public function getPizzaMenus() {
		return $this->LanPizzaMenu->find('all', array(
					'conditions' => array(
						'LanPizzaMenu.lan_id' => $this->id
					)
						)
		);
	}

	public function getPizzaMenuIds() {
		$lan_pizza_menus = $this->LanPizzaMenu->find('all', array(
			'conditions' => array(
				'LanPizzaMenu.lan_id' => $this->id
			),
			'fields' => array(
				'LanPizzaMenu.pizza_menu_id'
			),
			'recursive' => -1
				)
		);

		$ids = array();
		foreach ($lan_pizza_menus as $lan_pizza_menu) {
			$ids[] = $lan_pizza_menu['LanPizzaMenu']['pizza_menu_id'];
		}

		return $ids;
	}

	public function getFoodMenuIds() {
		$lan_food_menus = $this->LanFoodMenu->find('all', array(
			'conditions' => array(
				'LanFoodMenu.lan_id' => $this->id
			),
			'fields' => array(
				'LanFoodMenu.food_menu_id'
			)
				)
		);

		$food_menu_ids = array();
		foreach ($lan_food_menus as $lan_food_menu) {
			$food_menu_ids[] = $lan_food_menu['LanFoodMenu']['food_menu_id'];
		}

		return $food_menu_ids;
	}

	public function countPizzaOrders() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT COUNT(PizzaOrder.id) AS PizzaOrders FROM `lan_pizza_menus` AS LanPizzaMenu INNER JOIN `pizza_waves` AS PizzaWave ON PizzaWave.lan_pizza_menu_id = LanPizzaMenu.id INNER JOIN `pizza_orders` AS PizzaOrder ON PizzaOrder.pizza_wave_id = PizzaWave.id WHERE LanPizzaMenu.lan_id = ?", array($this->id));
		return $total[0][0]['PizzaOrders'];
	}

	public function getMoneyTotalPizzas() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT SUM(PizzaOrderItem.price * PizzaOrderItem.quantity) AS Total FROM `lan_pizza_menus` AS LanPizzaMenu INNER JOIN `pizza_waves` AS PizzaWave ON PizzaWave.lan_pizza_menu_id = LanPizzaMenu.id INNER JOIN `pizza_orders` AS PizzaOrder ON PizzaOrder.pizza_wave_id = PizzaWave.id INNER JOIN pizza_order_items AS PizzaOrderItem ON PizzaOrderItem.pizza_order_id = PizzaOrder.id WHERE LanPizzaMenu.lan_id = ?", array($this->id));
		return $total[0][0]['Total'];
	}

	public function countFoodOrders() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT COUNT(FoodOrder.id) AS FoodOrders FROM `lan_food_menus` AS LanFoodMenu INNER JOIN `food_orders` AS FoodOrder ON FoodOrder.lan_food_menu_id = LanFoodMenu.id WHERE LanFoodMenu.lan_id = ?", array($this->id));
		return $total[0][0]['FoodOrders'];
	}

	public function getMoneyTotalFoods() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT SUM(FoodOrderItem.price * FoodOrderItem.quantity) AS Total FROM `lan_food_menus` AS LanFoodMenu INNER JOIN `food_orders` AS FoodOrder ON FoodOrder.lan_food_menu_id = LanFoodMenu.id INNER JOIN `food_order_items` AS FoodOrderItem ON FoodOrderItem.food_order_id = FoodOrder.id WHERE LanFoodMenu.lan_id = ?", array($this->id));
		return $total[0][0]['Total'];
	}

	public function getIndexList() {
		return $this->find('all', array(
					'recursive' => 1
						)
		);
	}

	public function beforeSave($options = array()) {
		parent::beforeSave($options);

		if (isset($this->data['Lan']['title'])) {
			$this->data['Lan']['slug'] = $this->stringToSlug($this->data['Lan']['title']);
		}

		return true;
	}

	public function generateLanSignupCodes($quantity) {

		$codes = $this->LanSignupCode->find('list');

		$data = array();
		$generated = 0;
		while ($generated < $quantity) {
			$new = $this->generateRandomString();

			if (!in_array($new, $codes)) {
				$data[$generated]['code'] = $codes[] = $new;
				$generated++;
			}
		}
		return $data;
	}

}

?>
