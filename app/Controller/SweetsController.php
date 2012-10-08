<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SweetsController
 *
 * @author Jens
 */
class SweetsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)) {
			return true;
		}
		return false;
	}

	public function index() {
		$title_for_layout = 'Sweets and soda';

		$sweets = array();

		$conditions = array();

		if (!$this->isAdmin()) {
			$conditions['Sweet.available'] = 1;
		}

		$sweets = $this->Sweet->find('all', array(
			'conditions' => $conditions
				)
		);

		$this->loadModel('Lan');
		if ($this->Lan->isCurrent($this->isAdmin())) {
			$current_lan = $this->Lan->getCurrent($this->isAdmin());

			if ($this->Lan->isUserAttending($current_lan['Lan']['id'], $this->Auth->user('id'))) {
				$this->set('sweets_current_lan_id', $current_lan['Lan']['id']);
			}
		}

		$this->set(compact('title_for_layout', 'sweets'));
	}

	public function add() {
		if ($this->request->is('post')) {

			if ($this->Sweet->save($this->request->data)) {
				$this->Session->setFlash('Your sweet has been created.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to create sweet', 'default', array(), 'bad');
			}
		}
	}

	public function edit($id) {
		$this->Sweet->id = $id;
		if (!$this->Sweet->exists()) {
			throw new NotFoundException(__('Sweet not found'));
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->Sweet->read();
		} else {
			if ($this->Sweet->save($this->request->data)) {
				$this->Session->setFlash(__('The sweet has been saved'), 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The sweet could not be saved - please try again'), 'default', array(), 'bad');
			}
		}
	}

	public function delete($id) {
		$this->Sweet->id = $id;

		if (!$this->Sweet->exists()) {
			throw new NotFoundException(__('Sweet not found'));
		}

		if ($this->Sweet->delete()) {
			$this->Session->setFlash('Sweet has been deleted', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('Sweet could not be deleted', 'default', array(), 'bad');
			$this->redirect(array('action' => 'index'));
		}
	}

}

?>
