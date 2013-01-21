<?php

class Lan extends AppModel {

	//
	// Has Many
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
	//
	//Validation
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

	/*
	 * 
	 * Validate inputs time_start + time_end
	 * 
	 */

	public function validateDates($check) {
		if ($check['time_start'] >= $this->data['Lan']['time_end']) {
			$this->invalidate('time_end', 'Invalid start-/end-time');
			return false;
		}
		return true;
	}

	/*
	 * 
	 * Count how many seats left in Lan
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function countSeatsLeft() {
		$this->read(array('max_participants'));

		return $this->data['Lan']['max_participants'] - $this->countSignups();
	}

	/*
	 * 
	 * Count signups for Lan
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function countSignups() {
		return $this->LanSignup->find('count', array(
						'conditions' => array(
							 'LanSignup.lan_id' => $this->id
						)
							 )
		);
	}

	/*
	 * 
	 * Count guests in Lan
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function countGuests() {
		return $this->LanSignup->find('count', array(
						'conditions' => array(
							 'LanSignup.lan_id' => $this->id,
							 'User.type' => 'guest'
						)
							 )
		);
	}

	/*
	 * 
	 * Count quantity of Pizza orders for specific LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function countPizzaOrders() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT COUNT(PizzaOrder.id) AS PizzaOrders FROM `lan_pizza_menus` AS LanPizzaMenu INNER JOIN `pizza_waves` AS PizzaWave ON PizzaWave.lan_pizza_menu_id = LanPizzaMenu.id INNER JOIN `pizza_orders` AS PizzaOrder ON PizzaOrder.pizza_wave_id = PizzaWave.id WHERE LanPizzaMenu.lan_id = ?", array($this->id));
		return $total[0][0]['PizzaOrders'];
	}

	/*
	 * 
	 * Count Tournaments in Lan
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function countTournaments() {
		return $this->Tournament->find('count', array(
						'conditions' => array(
							 'Tournament.lan_id' => $this->id
						)
							 )
		);
	}

	/*
	 * 
	 * Generate Lan Signup Codes
	 * 
	 * Used when a LAN is created
	 * 
	 * Required
	 * 	- $quantity, how many codes has to be generated?
	 */

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

	/*
	 * 
	 * Get general statistics for the General tab
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function getGeneralStatistics() {
		$this->read(array('max_participants'));
		$count_tournaments = $this->countTournaments();
		$count_signups = $this->countSignups();
		$count_signups_guests = $this->countGuests();
		$count_signups_students = $count_signups - $count_signups_guests;

		$fill_rate = $count_signups === 0 ? 0 : $this->floordec($count_signups / $this->data['Lan']['max_participants'] * 100);

		$percentage_students = $count_signups === 0 ? 0 : $this->floordec($count_signups_students / $count_signups * 100);
		$percentage_guests = $count_signups_guests === 0 ? 0 : 100 - $percentage_students;

		return array(
			 'count_tournaments' => $count_tournaments,
			 'count_signups' => $count_signups,
			 'count_signups_students' => $count_signups_students,
			 'count_signups_guests' => $count_signups_guests,
			 'fill_rate' => $fill_rate,
			 'percentage_students' => $percentage_students,
			 'percentage_guests' => $percentage_guests
		);
	}

	/*
	 * 
	 * Get Lan Id by Slug
	 * 
	 * Required
	 * 	- $slug
	 */

	public function getIdBySlug($slug) {

		$result = $this->find('first', array(
			 'conditions' => array(
				  'slug' => $slug
			 ),
			 'fields' => array(
				  'id',
				  'published'
			 )
				  ));

		if (!isset($result['Lan']['id'])) {
			throw new NotFoundException('Lan not found with slug: ' . $slug);
		}

		$this->id = $result['Lan']['id'];

		if (!$this->exists()) {
			throw new NotFoundException('Lan not found with slug: ' . $slug);
		}

		$this->LanSignup->User->id = $this->getLoggedInId();
		if (!($result['Lan']['published'] || $this->isYouAdmin() || $this->isUserAttendingAsCrew())) {
			throw new UnauthorizedException('You are not authorized to see this page');
		}

		return $this->id;
	}

	public function getCrewData() {
		return $this->Crew->User->find('all', array(
						'conditions' => array(
							 'User.id' => $this->getCrewMembersUserIds()
						),
						'fields' => array(
							 'User.id',
							 'User.name',
							 'User.email_gravatar',
						)
				  ));
	}

	/*
	 * 
	 * Get Crewmembers User-ids for a specific LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function getCrewMembersUserIds() {
		$lan_crews = $this->Crew->find('all', array('conditions' => array(
				  'Crew.lan_id' => $this->id
			 ),
			 'fields' => array(
				  'Crew.user_id'
			 ),
				  )
		);

		$lan_crew_ids = array();
		foreach ($lan_crews as $crew) {
			$lan_crew_ids[] = $crew['Crew']['user_id'];
		}

		return $lan_crew_ids;
	}

	/*
	 * 
	 * Get Lan Pizza Menus for a specific LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function getLanPizzaMenus() {
		return $this->LanPizzaMenu->find('all', array(
						'conditions' => array(
							 'LanPizzaMenu.lan_id' => $this->id
						)
							 )
		);
	}

	/*
	 * 
	 * Get Pizza menus connected to a LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

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

	/*
	 * 
	 * Get Food menu ids connected to specific LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id has to be initialized
	 */

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

	/*
	 * 
	 * Get highlighted LANS
	 */

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

	/*
	 * 
	 * Is Lan published?
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

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

	/*
	 * 
	 * Is signup open for Lan?
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function isSignupOpen() {

		if (!$this->exists()) {
			throw new NotFoundException('Lan not found with id: ' . $this->id);
		}

		$this->read(array('sign_up_open'));

		return $this->data['Lan']['sign_up_open'];
	}

	/*
	 * 
	 * Is Lan in the past?
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function isPast() {

		$this->read(array('time_end'));

		return $this->data['Lan']['time_end'] < date('Y-m-d H:i:s');
	}

	/*
	 * 
	 * Is signup possible in Lan?
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function isSignupPossible() {
		if ($this->isPublished() && $this->isSignupOpen() && $this->isSeatsLeft()) {
			return true;
		}

		return false;
	}

	/*
	 * 
	 * Is there seats free in Lan?
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function isSeatsLeft() {
		return $this->countSeatsLeft > 0;
	}

	/*
	 * 
	 * Is User able to sign up in Lan?
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 * 	- $this(->Lan)->LanSignup->User->id
	 */

	public function isUserAbleSignup() {

		if (!$this->isUserAttending() && isSignupPossible()) {
			return true;
		}

		return false;
	}

	/*
	 * 
	 * Is User atttending Lan? (either signed up or in crew)
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 * 	- $this(->Lan)->LanSignup->User->id
	 */

	public function isUserAttending() {
		return $this->isUserAttendingAsGuest() || $this->isUserAttendingAsCrew();
	}

	public function isUserAttendingAsGuest() {
		return $this->LanSignup->find('count', array('conditions' => array(
							 'LanSignup.lan_id' => $this->id,
							 'LanSignup.user_id' => $this->LanSignup->User->id
						)
							 )
				  ) == 1;
	}

	public function isUserAttendingAsCrew() {
		return $this->Crew->find('count', array('conditions' => array(
							 'Crew.lan_id' => $this->id,
							 'Crew.user_id' => $this->LanSignup->User->id
						)
							 )
				  ) == 1;
	}

	/*
	 * 
	 * Get total money for pizzas for specific LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 * 
	 */

	public function getMoneyTotalPizzas() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT SUM(PizzaOrderItem.price * PizzaOrderItem.quantity) AS Total FROM `lan_pizza_menus` AS LanPizzaMenu INNER JOIN `pizza_waves` AS PizzaWave ON PizzaWave.lan_pizza_menu_id = LanPizzaMenu.id INNER JOIN `pizza_orders` AS PizzaOrder ON PizzaOrder.pizza_wave_id = PizzaWave.id INNER JOIN pizza_order_items AS PizzaOrderItem ON PizzaOrderItem.pizza_order_id = PizzaOrder.id WHERE LanPizzaMenu.lan_id = ?", array($this->id));
		return $total[0][0]['Total'];
	}

	/*
	 * 
	 * Count quantity of Food orders for specific LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function countFoodOrders() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT COUNT(FoodOrder.id) AS FoodOrders FROM `lan_food_menus` AS LanFoodMenu INNER JOIN `food_orders` AS FoodOrder ON FoodOrder.lan_food_menu_id = LanFoodMenu.id WHERE LanFoodMenu.lan_id = ?", array($this->id));
		return $total[0][0]['FoodOrders'];
	}

	/*
	 * 
	 * Get total money for food for specific LAN
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function getMoneyTotalFoods() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT SUM(FoodOrderItem.price * FoodOrderItem.quantity) AS Total FROM `lan_food_menus` AS LanFoodMenu INNER JOIN `food_orders` AS FoodOrder ON FoodOrder.lan_food_menu_id = LanFoodMenu.id INNER JOIN `food_order_items` AS FoodOrderItem ON FoodOrderItem.food_order_id = FoodOrder.id WHERE LanFoodMenu.lan_id = ?", array($this->id));
		return $total[0][0]['Total'];
	}

	/*
	 * 
	 * List of Lans (index)
	 * 
	 */

	public function getIndexList() {
		return $this->find('all', array(
						'recursive' => 1
							 )
		);
	}

	/*
	 * 
	 * Tabs for LAN-page
	 * 
	 * Required
	 * 	- $this(->Lan)->id
	 */

	public function getTabs() {

		$this->read(array('slug'));

		$tabs = array(
			 array(
				  'title' => 'Generel',
				  'url' => array(
						'action' => 'view_general',
						$this->data['Lan']['slug']
				  ),
				  'img' => '24x24_PNG/001_40.png',
			 ),
			 array(
				  'title' => 'Crew',
				  'url' => array(
						'action' => 'view_crew',
						$this->data['Lan']['slug']
				  ),
				  'img' => '24x24_PNG/crew.png',
			 ),
			 array(
				  'title' => 'Participants',
				  'url' => array(
						'action' => 'view_participants',
						$this->data['Lan']['slug']
				  ),
				  'img' => '24x24_PNG/participants.png',
			 ),
			 array(
				  'title' => 'Tournaments',
				  'url' => array(
						'action' => 'view_tournaments',
						$this->data['Lan']['slug']
				  ),
				  'img' => '24x24_PNG/trophy_gold.png',
			 ),
		);

		if ($this->isYouAdmin()) {
			$tabs_admin = array(
				 array(
					  'title' => 'Food menus',
					  'url' => array(
							'action' => 'view_foodmenus',
							$this->data['Lan']['slug']
					  ),
					  'img' => '24x24_PNG/candy.png',
				 ),
				 array(
					  'title' => 'Pizza menus',
					  'url' => array(
							'action' => 'view_pizzamenus',
							$this->data['Lan']['slug']
					  ),
					  'img' => '24x24_PNG/pizza.png',
				 ),
				 array(
					  'title' => 'Economics',
					  'url' => array(
							'action' => 'view_economics',
							$this->data['Lan']['slug']
					  ),
					  'img' => '24x24_PNG/payment_cash.png',
				 ),
			);

			$tabs = array_merge($tabs, $tabs_admin);
		}


		if ($this->isLoggedIn() && $this->isUserAbleSignup()) {
			$tabs_signup = array(
				 array(
					  'title' => 'Sign up',
					  'url' => array(
							'controller' => 'lan_signups',
							'action' => 'add',
							$this->data['Lan']['slug']
					  ),
					  'img' => '24x24_PNG/001_01.png',
				 ),
			);

			$tabs = array_merge($tabs, $tabs_signup);
		}

		return $tabs;
	}

	/*
	 * 
	 * Before save
	 */

	public function beforeSave($options = array()) {
		parent::beforeSave($options);

		if (isset($this->data['Lan']['title'])) {
			$this->data['Lan']['slug'] = $this->stringToSlug($this->data['Lan']['title']);
		}

		return true;
	}

}

?>
