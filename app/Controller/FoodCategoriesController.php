<?php

class FoodCategoriesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->FoodCategory->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function add($food_menu_id) {
		$this->FoodCategory->FoodMenu->id = $food_menu_id;

		if (!$this->FoodCategory->FoodMenu->exists()) {
			throw new NotFoundException('Food Menu not found with ID #' . $food_menu_id);
		}

		$this->set('food_menu', $this->FoodCategory->FoodMenu->read(array('id', 'title')));

		if ($this->request->is('post')) {

			$this->request->data['FoodCategory']['food_menu_id'] = $food_menu_id;

			if ($this->FoodCategory->save($this->request->data)) {
				$this->Session->setFlash('Your Food Category has been created', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('controller'=>'food_menus','action' => 'view', $food_menu_id));
			} else {
				$this->Session->setFlash('Unable to create food', 'default', array(), 'bad');
			}
		}
	}

	public function edit($id) {

	}

}

?>
