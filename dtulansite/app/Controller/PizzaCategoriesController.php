<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaCategoriesController
 *
 * @author Jens
 */
class PizzaCategoriesController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow('index');
	}

	public function index() {
		$this->set('pizza_categories', $this->PizzaCategory->find('all', array('conditions' =>
					array(
						'PizzaCategory.available' => 1
					),
					'recursive' => 3)
				)
		);
	}

}

?>
