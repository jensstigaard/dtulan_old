<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @author Jens
 */
class AdminsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);
		return true;
	}

	public function add() {

		if ($this->request->is('post')) {

			$this->Admin->create();
			if ($this->Admin->save($this->request->data)) {
				$this->Session->setFlash(__('User made admin'), 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash(__('Errors during saving'), 'default', array(), 'bad');
			}
		}

		$users = $this->Admin->User->getUserIDsNotAdmin();

		$this->set(compact('users'));
	}

}

?>
