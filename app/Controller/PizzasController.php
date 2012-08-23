<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzasController
 *
 * @author Jens
 */
class PizzasController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function add($category_id = null) {

		if($category_id == null){
			throw new NotFoundException('Category not found');
		}

		$this->Pizza->PizzaCategory->id = $category_id;

		if(!$this->Pizza->PizzaCategory->exists()){
			throw new NotFoundException('Category not found');
		}

		if ($this->request->is('post')) {
			debug($this->request->data);
//
//			if ($this->Pizza->save($this->request->data)) {
//				$this->Session->setFlash('Your payment has been saved.');
//				$this->redirect(array('controller' => 'pizza_categories', 'action' => 'index'));
//			} else {
//				$this->Session->setFlash('Unable to add your pizza.');
//			}
		}

		$this->Pizza->PizzaCategory->recursive = 2;
		$pizza_category = $this->Pizza->PizzaCategory->find('first');

		$this->set(compact('pizza_category'));
	}
}

?>
