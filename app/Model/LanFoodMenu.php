<?php

class LanFoodMenu extends AppModel {
	public $belongsTo = array(
		'Lan',
		'FoodMenu'
	);

	public $hasMany = array(
		'FoodOrder'
	);

	public function getCategories(){
		return $this->FoodMenu->getCategories();
	}

	public function countOrders(){
		return $this->FoodOrder->find('count', array(
			'conditions' => array(
				'FoodOrder.lan_food_menu_id' => $this->id
			)
		));
	}
}

?>
