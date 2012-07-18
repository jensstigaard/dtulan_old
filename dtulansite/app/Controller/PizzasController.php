<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaController
 *
 * @author Jens
 */
class PizzasController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function index() {
		$this->set('pizza_categories', $this->PizzaCategory->find('all'));
	}

}

?>
