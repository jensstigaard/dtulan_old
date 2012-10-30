<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FoodsController
 *
 * @author Jens
 */
class FoodsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Food->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function index() {
		$title_for_layout = 'Foods and soda';

		$foods = array();

		$conditions = array();

		if (!$this->Food->isYouAdmin()) {
			$conditions['Food.available'] = 1;
		}

		$foods = $this->Food->find('all', array(
			'conditions' => $conditions
				)
		);

		$this->loadModel('Lan');
		if ($this->Lan->isCurrent()) {
			$current_lan = $this->Lan->getCurrent();

			if ($this->Lan->isUserAttending($current_lan['Lan']['id'], $this->Auth->user('id'))) {
				$this->set('foods_current_lan_id', $current_lan['Lan']['id']);
			}
		}

		$this->set(compact('title_for_layout', 'foods'));
	}

	public function add() {
		if ($this->request->is('post')) {

			if ($this->Food->save($this->request->data)) {
				$this->Session->setFlash('Your food has been created', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to create food', 'default', array(), 'bad');
			}
		}
	}

	public function edit($id) {
		$this->Food->id = $id;
		if (!$this->Food->exists()) {
			throw new NotFoundException(__('food not found'));
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->Food->read();
		} else {
			if ($this->Food->save($this->request->data)) {
				$this->Session->setFlash(__('The food has been saved'), 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The food could not be saved - please try again'), 'default', array(), 'bad');
			}
		}
	}

	public function delete($id) {
		$this->Food->id = $id;

		if (!$this->Food->exists()) {
			throw new NotFoundException(__('Food not found'));
		}

		if ($this->Food->delete()) {
			$this->Session->setFlash('Food has been deleted', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('Food could not be deleted', 'default', array(), 'bad');
			$this->redirect(array('action' => 'index'));
		}
	}

}

?>
