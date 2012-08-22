<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignupsController
 *
 * @author Jens
 */
class LanSignupsController extends AppController {

	public function add($id = null) {

		App::uses('CakeTime', 'Utility');

		$user = $this->Auth->user();

		$this->LanSignup->User->id = $user['id'];

		if (!$this->LanSignup->User->exists()) {
			throw new NotFoundException(__('Invalid User'));
		}

		if ($id == null) {
			throw new NotFoundException(__('Invalid LAN'));
		}

		$user = $this->LanSignup->User->read();

		$lans = array();
		foreach ($user['LanSignup'] as $lan) {
			$lans[] = $lan['lan_id'];
		}

		$lan = $this->LanSignup->Lan->find('first', array(
			'conditions' => array(
				'id' => $id,
				'NOT' => array(
					'Lan.id' => $lans
				)
			)
				)
		);

		if (!$lan) {
			throw new NotFoundException(__('LAN already signed up or no LAN found with given ID'));
		}

		if ($this->request->is('post')) {

			$this->request->data['LanSignup']['lan_id'] = $id;
			$this->request->data['LanSignup']['user_id'] = $user['User']['id'];

			if ($this->LanSignup->save($this->request->data)) {
				$this->Session->setFlash('Your signup has been saved.');
				$this->redirect(array('controller' => 'users', 'action' => 'profile'));
			} else {
				$this->Session->setFlash('Unable to add your signup.');
			}
		}

		$lan_days = array();
		foreach ($lan['LanDay'] as $lan_day) {
			$lan_days[$lan_day['id']] = CakeTime::format('l F jS Y', $lan_day['date']);
		}

		$this->set(compact('lan', 'lan_days', 'user'));
	}

}

?>
