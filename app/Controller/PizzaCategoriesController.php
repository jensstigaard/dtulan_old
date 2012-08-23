<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaCategoriesController
 *
 * @author Jens
 */
class PizzaCategoriesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function index() {

		$this->set('title_for_layout', 'Pizzas');

		$data_category = $this->PizzaCategory->find('all', array('conditions' =>
			array(
				'PizzaCategory.available' => 1
			),
			'recursive' => 2)
		);

		$data_prices = array();
		foreach ($data_category as $category_id => $category) {
			foreach ($category['Pizza'] as $pizza_id => $pizza) {
				foreach ($pizza['PizzaPrice'] as $price) {
					$data_prices[$price['pizza_type_id']][$pizza['id']]['price'] = $price['price'];
					$data_prices[$price['pizza_type_id']][$pizza['id']]['pizza_price_id'] = $price['id'];
				}

				foreach ($category['PizzaCategoryType'] as $type) {
					$price = 0;
					if (isset($data_prices[$type['PizzaType']['id']][$pizza['id']])) {
						$price = $data_prices[$type['PizzaType']['id']][$pizza['id']]['price'];
						$id = $data_prices[$type['PizzaType']['id']][$pizza['id']]['pizza_price_id'];
					}
					$data_category[$category_id]['Pizza'][$pizza_id]['Prices'][$type['PizzaType']['id']]['price'] = $price;
					$data_category[$category_id]['Pizza'][$pizza_id]['Prices'][$type['PizzaType']['id']]['id'] = $id;
				}
			}
		}

		$this->set('pizza_categories', $data_category);
		$this->set('isOrderable', $this->Auth->loggedIn());
//		$this->set('pizza_prices', $data_prices);
	}

}

?>
