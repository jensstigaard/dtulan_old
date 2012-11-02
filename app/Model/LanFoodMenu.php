<?php

class LanFoodMenu extends AppModel {
	public $belongsTo = array(
		'Lan',
		'FoodMenu'
	);

	public function getCategories(){

		return $this->FoodMenu->getCategories();
	}
}

?>
