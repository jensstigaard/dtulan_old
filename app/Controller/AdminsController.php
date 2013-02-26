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

		if ($this->Admin->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function admin_index() {
		$this->set('admins', $this->Admin->find('all', array(
						'contain' => array(
							 'User' => array(
								  'fields' => array(
										'id',
										'name',
										'email_gravatar',
										'phonenumber'
								  )
							 )
						)
				  )));
	}

	public function admin_add() {

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
