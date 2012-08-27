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

		$this->set('pizza_categories', $data_category);
		$this->set('isOrderable', $this->Auth->loggedIn());
	}

	public function add() {
		if ($this->request->is('post')) {

			foreach ($this->request->data['PizzaType'] as $type_id => $type_data) {
				if (!$type_data['pizza_type_id'] > 0) {
					unset($this->request->data['PizzaType'][$type_id]);
				}
			}

			if ($this->PizzaCategory->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your category has been created.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to create category.');
			}
		}

		$this->set('types', $this->PizzaCategory->PizzaType->find('list'));
	}

	public function edit($id = null) {

		if ($id == null) {
			throw new NotFoundException('Pizza category not found');
		}

		$this->PizzaCategory->id = $id;

		if (!$this->PizzaCategory->exists()) {
			throw new NotFoundException('Pizza category not found');
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			$this->request->data['PizzaCategory']['id'] = $id;

			foreach ($this->request->data['PizzaType'] as $type_id_x => $type_data) {
				if ($type_data['pizza_type_id'] > 0) {
					$this->request->data['PizzaType'][$type_id_x]['pizza_category_id'] = $id;
				} else {
					unset($this->request->data['PizzaType'][$type_id_x]);
				}
			}

			if ($this->PizzaCategory->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Category has been updated.');
				debug($this->request->data);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to update category.');
			}
		} else {
			$this->request->data = $this->PizzaCategory->read(null, $id);
		}

		$this->set('pizza_category', $this->request->data);

		$this->set('types', $this->PizzaCategory->PizzaType->find('list'));

		$types_selected = array();
		foreach ($this->request->data['PizzaType'] as $type) {
			$types_selected[$type['PizzaCategoryType']['pizza_type_id']] = true;
		}

		$this->set(compact('types_selected'));
	}

}

?>
