<?php

class FoodsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Food->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function add($food_category_id) {

		$this->Food->FoodCategory->id = $food_category_id;
		if(!$this->Food->FoodCategory->exists()){
			throw new NotFoundException('Food Category not found with ID #'.$food_category_id);
		}

		$this->set('category', $this->Food->FoodCategory->read(array('id', 'title')));

		if ($this->request->is('post')) {

			$this->request->data['Food']['food_category_id'] = $food_category_id;

			if ($this->Food->save($this->request->data)) {
				$this->Session->setFlash('Your food has been created', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('controller' => 'food_menus'));
			} else {
				$this->Session->setFlash('Unable to create food', 'default', array(), 'bad');
			}
		}
	}

	public function edit($id) {
		$this->Food->id = $id;
		if (!$this->Food->exists()) {
			throw new NotFoundException(__('Food not found with ID #'.$id));
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->Food->read();
		} else {
			if ($this->Food->save($this->request->data)) {
				$this->Session->setFlash(__('The food has been saved'), 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The food could not be saved - please try again'), 'default', array(), 'bad');
			}
		}

		$this->set('category', $this->Food->FoodCategory->read(array('id','title'), $this->Food->field('food_category_id')));
	}

	public function delete($id) {
		$this->Food->id = $id;

		if (!$this->Food->exists()) {
			throw new NotFoundException(__('Food not found'));
		}

		if ($this->Food->delete()) {
			$this->Session->setFlash('Food has been deleted', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Food could not be deleted', 'default', array(), 'bad');
		}

		$this->redirect($this->referer());
	}

}

?>
