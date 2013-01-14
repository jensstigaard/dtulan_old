<?php

class PizzaTypesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->PizzaType->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->set('pizza_types', $this->PizzaType->find('all'));
	}

	public function add() {
		if ($this->request->is('post')) {

			if ($this->PizzaType->save($this->request->data)) {
				$this->Session->setFlash('Pizza-type has been added.', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to add this pizza-type.', 'default', array(), 'bad');
			}

			$this->redirect($this->referer());
		}
	}

}