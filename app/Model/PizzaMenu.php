<?php

class PizzaMenu extends AppModel {

	public $hasMany = array(
		'PizzaCategory',
		'LanPizzaMenu'
	);
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Title cannot be empty'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Title has to be unique'
			)
		)
	);
	
	public function getIndexList(){
		$pizza_menus = $this->find('all', array(
			'recursive' => -1
				));

		foreach ($pizza_menus as $index => $data) {
			$this->id = $data['PizzaMenu']['id'];
			$pizza_menus[$index]['PizzaMenu']['count_categories'] = $this->countCategories();
			$pizza_menus[$index]['PizzaMenu']['count_items'] = $this->countItems();
			$pizza_menus[$index]['PizzaMenu']['count_used'] = $this->countUsedInLans();
		}
		
		return $pizza_menus;
	}

	public function getPizzaList() {
		$this->PizzaCategory->Pizza->unbindModel(array('belongsTo' => array('PizzaCategory')));
		$this->PizzaCategory->Pizza->PizzaPrice->unbindModel(array('belongsTo' => array('Pizza'), 'hasMany' => array('PizzaOrderItem')));

		$conditions = array();
		if (!$this->isYouAdmin()) {
			$conditions['PizzaCategory.available'] = 1;
		}
		$conditions =
				$data_category = $this->PizzaCategory->find('all', array('conditions' => $conditions,
			'recursive' => 3)
		);

		$data_prices = array();
		foreach ($data_category as $category_id => $category) {
			foreach ($category['Pizza'] as $pizza_id => $pizza) {
				foreach ($pizza['PizzaPrice'] as $price) {
					$data_prices[$price['pizza_type_id']][$pizza['id']]['price'] = $price['price'];
					$data_prices[$price['pizza_type_id']][$pizza['id']]['pizza_price_id'] = $price['id'];
				}



				foreach ($category['PizzaType'] as $type) {
					$price = 0;
					if (isset($data_prices[$type['id']][$pizza['id']])) {
						$price = $data_prices[$type['id']][$pizza['id']]['price'];
						$id = $data_prices[$type['id']][$pizza['id']]['pizza_price_id'];
					}
					$data_category[$category_id]['Pizza'][$pizza_id]['Prices'][$type['id']]['price'] = $price;

					if (isset($id) && $id > 0) {
						$data_category[$category_id]['Pizza'][$pizza_id]['Prices'][$type['id']]['id'] = $id;
					}
				}
			}
		}

		return $data_category;
	}

	public function countCategories() {
		return $this->PizzaCategory->find('count', array(
					'conditions' => array(
						'PizzaCategory.pizza_menu_id' => $this->id
					)
				));
	}

	public function countItems() {
		$db = $this->getDataSource();
		$total = $db->fetchAll("SELECT COUNT(Pizza.id) AS countItems FROM `pizzas` AS Pizza INNER JOIN `pizza_categories` AS PizzaCategory ON Pizza.pizza_category_id = PizzaCategory.id WHERE PizzaCategory.pizza_menu_id = ?", array($this->id));
		return $total[0][0]['countItems'];
	}

	public function getUsedInLans() {
		return $this->LanPizzaMenu->find('all', array(
					'conditions' => array(
						'LanPizzaMenu.pizza_menu_id' => $this->id
					),
					'recursive' => 1
				));
	}

	public function countUsedInLans() {
		return $this->LanPizzaMenu->find('count', array(
					'conditions' => array(
						'LanPizzaMenu.pizza_menu_id' => $this->id
					)
				));
	}

}

?>
