<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CrewController
 *
 * @author Jens
 */
class CrewController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Crew->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function add($lan_id) {
		$this->Crew->Lan->id = $lan_id;

		if ($this->Crew->Lan->exists()) {
			throw new NotFoundException('Lan not found');
		}

		$this->Crew->Lan->User->id = $this->Auth->user('id');

		if ($this->Crew->save($this->request->data)) {
			$this->Session->setFlash('Crew has been saved', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Unable to create food', 'default', array(), 'bad');
		}

		$this->redirect($this->referer());
	}

}

?>
