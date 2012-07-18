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
		$this->Auth->allow('index');
	}

	public function index() {
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
					$data_prices[$price['pizza_type_id']][$pizza['id']] = $price['price'];
				}

				foreach ($category['PizzaCategoryType'] as $type) {
					$price = 0;
					if (isset($data_prices[$type['PizzaType']['id']][$pizza['id']])) {
						$price = $data_prices[$type['PizzaType']['id']][$pizza['id']];
					}
					$data_category[$category_id]['Pizza'][$pizza_id]['Prices'][] = $price;
				}
			}
		}




		$this->set('pizza_categories', $data_category);
		$this->set('pizza_prices', $data_prices);
	}

}

?>
