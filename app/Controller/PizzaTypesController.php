<?php

class PizzaTypesController extends AppController {

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

	public function add(){
		if ($this->request->is('post')) {

			if ($this->PizzaType->save($this->request->data)) {
				$this->Session->setFlash('Pizza-type has been added.');
				$this->redirect(array('controller'=> 'pizza_categories' ,'action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add this pizza-type.');
			}
		}
	}
}

?>
