<?php

class PizzaTypesController extends AppController {
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
