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
				$this->Session->setFlash(__('User made admin.'), 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash(__('Errors during saving'), 'default', array(), 'bad');
			}
		}

		$this->Admin->recursive = 0;
		$users_admin = $this->Admin->find('all');

		$users_admin_ids = array();
		foreach ($users_admin as $admin) {
			$users_admin_ids[] = $admin['Admin']['user_id'];
		}

		$users = $this->Admin->User->find('list', array(
			'conditions' => array(
				'User.activated' => 1,
				'NOT' => array(
					'User.id' => $users_admin_ids
				)
			)
				)
		);

		$this->set(compact('users'));
	}

}

?>
