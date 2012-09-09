<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzasController
 *
 * @author Jens
 */
class PizzasController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)) {
			return true;
		}
		return false;
	}

	public function add($category_id = null) {

		if ($category_id == null) {
			throw new NotFoundException('Category not found');
		}

		$this->Pizza->PizzaCategory->id = $category_id;

		if (!$this->Pizza->PizzaCategory->exists()) {
			throw new NotFoundException('Category not found');
		}


		if ($this->request->is('post')) {

			// Manipulate data	- Pizza category
			$this->request->data['Pizza']['pizza_category_id'] = $category_id;

			// Manipulate data	- Pizza prices
			foreach ($this->request->data['PizzaPrice'] as $price_type_id => $price_value) {
				if ($price_value['price'] > 0) {
					$this->request->data['PizzaPrice'][$price_type_id]['pizza_type_id'] = $price_type_id;
				} else {
					unset($this->request->data['PizzaPrice'][$price_type_id]);
				}
			}

//			debug($this->request->data);

			if ($this->Pizza->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your pizza has been saved.');
				$this->redirect(array('controller' => 'pizza_categories', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your pizza.');
			}
		}

		$this->Pizza->PizzaCategory->recursive = 2;
		$pizza_category = $this->Pizza->PizzaCategory->read();

		$this->set(compact('pizza_category'));
	}

	public function edit($id = null) {

		if ($id == null) {
			throw new NotFoundException('Pizza not found');
		}

		$this->Pizza->id = $id;

		if (!$this->Pizza->exists()) {
			throw new NotFoundException('Pizza not found');
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			// Manipulate data	- Pizza prices
			foreach ($this->request->data['PizzaPrice'] as $price_type_id => $price_value) {
				if ($price_value['price'] > 0) {
					$this->request->data['PizzaPrice'][$price_type_id] = array(
						'pizza_type_id' => $price_type_id,
						'pizza_id' => $id,
						'price' => $price_value['price']
					);

					$this->Pizza->PizzaPrice->unbindModel(array('belongsTo' => array('Pizza', 'PizzaType'), 'hasMany' => array('PizzaOrderItem')));
					$pizza_price = $this->Pizza->PizzaPrice->find('first', array(
						'conditions' => array(
							'pizza_id' => $id,
							'pizza_type_id' => $price_type_id
						)
							)
					);
					if(count($pizza_price)==1){
						$this->request->data['PizzaPrice'][$price_type_id]['id'] = $pizza_price['PizzaPrice']['id'];
//						debug($pizza_price);
					}
				} else {
					unset($this->request->data['PizzaPrice'][$price_type_id]);
				}
			}

			if ($this->Pizza->saveAssociated($this->request->data)) {

				$this->Session->setFlash('Your pizza has been saved.');
//				$this->redirect(array('controller' => 'pizza_categories', 'action' => 'index'));
//				debug($this->request->data);
			} else {
				$this->Session->setFlash('Unable to add your pizza.');
			}
		} else {
			$this->request->data = $this->Pizza->read(null, $id);
		}

		$this->Pizza->recursive = 2;
		$this->set('pizza', $this->Pizza->read());



//		$this->set(compact('pizza_prices'));
	}

}

?>
