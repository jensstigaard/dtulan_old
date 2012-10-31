<?php

class PizzaMenusController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->PizzaMenu->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function index() {
		$pizza_menus = $this->PizzaMenu->find('all', array(
			'recursive' => -1
				));

		foreach ($pizza_menus as $index => $data) {
			$this->PizzaMenu->id = $data['PizzaMenu']['id'];
			$pizza_menus[$index]['PizzaMenu']['count_categories'] = $this->PizzaMenu->countCategories();
			$pizza_menus[$index]['PizzaMenu']['count_items'] = $this->PizzaMenu->countItems();
		}

		$this->set(compact('pizza_menus'));
	}

}

?>
