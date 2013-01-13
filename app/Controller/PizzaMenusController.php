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
		$this->set('pizza_menus', $this->PizzaMenu->getIndexList());
	}

	public function view($id) {
		$this->PizzaMenu->id = $id;

		if (!$this->PizzaMenu->exists()) {
			throw new NotFoundException('PîzzaMenu not found with ID #' . $id);
		}

		$this->set('pizza_menu', $this->PizzaMenu->read());
		$this->set('used_in_lans', $this->PizzaMenu->getUsedInLans());
		$this->set('pizza_categories', $this->PizzaMenu->getPizzaList());
	}

	public function add() {
		if ($this->request->is('post')) {

			if ($this->PizzaMenu->save($this->request->data)) {
				$this->Session->setFlash('Pizza menu has been added', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to add pizza menu', 'default', array(), 'bad');
			}

			$this->redirect($this->referer());
		}
	}

	public function edit($id) {

		$this->PizzaMenu->id = $id;

		if (!$this->PizzaMenu->exists()) {
			throw new NotFoundException('PîzzaMenu not found with ID #' . $id);
		}

		if ($this->request->is('get')) {

			$this->request->data = $this->PizzaMenu->read();
		} else {
			if ($this->PizzaMenu->save($this->request->data)) {
				$this->Session->setFlash('Pizza menu has been saved', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to add pizza menu', 'default', array(), 'bad');
			}
		}

		$this->view = 'add';
	}

}

?>
