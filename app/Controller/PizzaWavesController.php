<?php

class PizzaWavesController extends AppController {

	public $helpers = array('Html', 'Form');

	public $uses = 'PizzaWave';

	public function index() {
		debug($this);
		$this->set('pizzaWaves', $this->PizzaWave->find('all'));
	}

	public function add() {
		if ($this->request->is('post')) {

			if ($this->PizzaWave->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your PizzaWave has been added.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add this PizzaWave.');
			}
		}
	}
}
?>
