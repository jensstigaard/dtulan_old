<?php

class FoodMenusController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->FoodMenu->isYouAdmin()) {
			return true;
		}

		return false;
	}

	public function index() {
		$food_menus = $this->FoodMenu->find('all', array(
			'recursive' => -1
				));

		foreach ($food_menus as $index => $data) {
			$this->FoodMenu->id = $data['FoodMenu']['id'];
			$food_menus[$index]['FoodMenu']['count_categories'] = $this->FoodMenu->countCategories();
			$food_menus[$index]['FoodMenu']['count_items'] = $this->FoodMenu->countItems();
			$food_menus[$index]['FoodMenu']['count_used'] = $this->FoodMenu->countUsedInLans();
		}

		$this->set(compact('food_menus'));
	}

	public function view($id) {
		$this->FoodMenu->id = $id;

		if (!$this->FoodMenu->exists()) {
			throw new NotFoundException('PîzzaMenu not found with ID #' . $id);
		}

		$this->set('food_menu', $this->FoodMenu->read());
		$this->set('used_in_lans', $this->FoodMenu->getUsedInLans());
		$this->set('categories', $this->FoodMenu->getCategories());
	}

	public function add() {
		if ($this->request->is('post')) {

			if ($this->FoodMenu->save($this->request->data)) {
				$this->Session->setFlash('Food Menu has been added', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to add Food Menu', 'default', array(), 'bad');
			}

			$this->redirect($this->referer());
		}
	}

	public function edit($id) {

		$this->FoodMenu->id = $id;

		if (!$this->FoodMenu->exists()) {
			throw new NotFoundException('Food Menu not found with ID #' . $id);
		}

		if ($this->request->is('get')) {

			$this->request->data = $this->FoodMenu->read();
		} else {

			if ($this->FoodMenu->save($this->request->data)) {
				$this->Session->setFlash('Food Menu has been saved', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to edit Food Menu', 'default', array(), 'bad');
			}
		}

		$this->view = 'add';
	}

}

?>
