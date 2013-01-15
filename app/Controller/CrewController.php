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

	public function add($lan_slug) {
		$this->Crew->Lan->id = $this->Crew->Lan->getIdBySlug($lan_slug);
		
		$this->Crew->Lan->read('title');
		
		$this->set('lan_title', $this->Crew->Lan->data['Lan']['title']);

		if ($this->request->is('post')) {
			$this->request->data['Crew']['lan_id'] = $this->Crew->Lan->id;
//			debug($this->request->data);
			if ($this->Crew->save($this->request->data)) {
				$this->Session->setFlash('Crew has been saved', 'default', array('class' => 'message success'), 'good');
				//$this->redirect($this->referer());
			} else {
				$this->Session->setFlash('Unable to save crewmember', 'default', array(), 'bad');
			}
		}


		$this->set('users', $this->Crew->getUsersNotCrew());
	}

}

?>
