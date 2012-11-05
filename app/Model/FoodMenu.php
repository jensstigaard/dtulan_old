<?php

class FoodMenu extends AppModel {

	public $hasMany = array(
		'FoodCategory',
		'LanFoodMenu'
	);

	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Title cannot be empty'
			)
		)
	);

	public function getCategories() {
		$this->FoodCategory->unbindModel(array('belongsTo' => array('FoodMenu')));

		$categories = $this->FoodCategory->find('all', array(
					'conditions' => array(
						'food_menu_id' => $this->id
					),
					'recursive' => 1
				));

		foreach($categories as $index => $category){
			foreach($category['Food'] as $index2 => $food){
				$this->FoodCategory->Food->id = $food['id'];
				$categories[$index]['Food'][$index2]['countTimesSold'] = $this->FoodCategory->Food->countTimesSold();
			}
		}

		return $categories;
	}

	public function countCategories() {
		return $this->FoodCategory->find('count', array(
					'conditions' => array(
						'food_menu_id' => $this->id
					)
				));
	}

	public function countItems() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT COUNT(Food.id) AS countItems FROM `foods` AS Food INNER JOIN `food_categories` AS FoodCategory ON Food.food_category_id = FoodCategory.id WHERE FoodCategory.food_menu_id = ?", array($this->id));
		return $total[0][0]['countItems'];
	}

	public function getUsedInLans() {
		return $this->LanFoodMenu->find('all', array(
					'conditions' => array(
						'food_menu_id' => $this->id
					),
					'recursive' => 1
				));
	}

	public function countUsedInLans() {
		return $this->LanFoodMenu->find('count', array(
					'conditions' => array(
						'food_menu_id' => $this->id
					)
				));
	}

}

?>
