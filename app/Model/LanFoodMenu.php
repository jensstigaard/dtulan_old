<?php

class LanFoodMenu extends AppModel {

	public $belongsTo = array(
		'Lan',
		'FoodMenu'
	);
	public $hasMany = array(
		'FoodOrder'
	);

	public function getCategories() {
		return $this->FoodMenu->getCategories();
	}

	public function getOrders() {

		return $orders;
	}

	public function countOrders() {
		return $this->FoodOrder->find('count', array(
					'conditions' => array(
						'FoodOrder.lan_food_menu_id' => $this->id
					)
				));
	}

	public function countOrdersUnhandled() {
		return $this->FoodOrder->find('count', array(
					'conditions' => array(
						'FoodOrder.lan_food_menu_id' => $this->id,
						'FoodOrder.status' => 0
					)
				));
	}

	public function isOrderable() {
		$this->read(array('is_orderable'));

		if ($this->data['LanFoodMenu']['is_orderable']) {
			return true;
		}

		return false;
	}

}

?>
