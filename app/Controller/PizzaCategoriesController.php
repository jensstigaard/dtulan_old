<?php

class PizzaCategoriesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->PizzaCategory->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function add($pizza_menu_id) {
		if ($this->request->is('post')) {

			foreach ($this->request->data['PizzaType'] as $type_id => $type_data) {
				if (!$type_data['pizza_type_id'] > 0) {
					unset($this->request->data['PizzaType'][$type_id]);
				}
			}

			$this->request->data['PizzaCategory']['pizza_menu_id'] = $pizza_menu_id;

			if ($this->PizzaCategory->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your category has been created', 'default', array('class' => 'message success'), 'good');
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash('Unable to create category', 'default', array(), 'bad');
			}
		}

		$this->set('types', $this->PizzaCategory->PizzaType->find('list'));
	}

	public function edit($id) {

		$this->PizzaCategory->id = $id;

		if (!$this->PizzaCategory->exists()) {
			throw new NotFoundException('Pizza category not found');
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->PizzaCategory->find('first', array(
				 'conditions' => array(
					  'PizzaCategory.id' => $this->PizzaCategory->id
				 ),
				 'contain' => array(
					  'PizzaType'
				 )
					  ));
		} else {
			$this->request->data['PizzaCategory']['id'] = $id;

			foreach ($this->request->data['PizzaType'] as $type_id_x => $type_data) {
				if ($type_data['pizza_type_id'] > 0) {
					$this->request->data['PizzaType'][$type_id_x]['pizza_category_id'] = $id;
				} else {
					unset($this->request->data['PizzaType'][$type_id_x]);
				}
			}

			if ($this->PizzaCategory->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Category has been updated.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to update category.', 'default', array(), 'bad');
			}
		}

		$this->set('pizza_category', $this->request->data);

		$this->set('types', $this->PizzaCategory->PizzaType->find('list'));

		$types_selected = array();

		foreach ($this->request->data['PizzaType'] as $type) {
			$types_selected[$type['id']] = true;
		}

		$this->set(compact('types_selected'));
	}

}