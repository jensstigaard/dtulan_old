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

			foreach ($this->request->data['LanSignupDay'] as $day_id => $day_value) {
				if ($day_value['lan_day_id'] == 0) {
					unset($this->request->data['LanSignupDay'][$day_id]);
				}
			}

//			debug($this->request->data);

//			echo $this->LanSignup->validates();
			if ($this->LanSignup->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your signup has been saved.');
				$this->redirect(array('controller' => 'users', 'action' => 'profile'));
			} else {
				$this->Session->setFlash('Unable to add your signup.');
			}
		}

		$lan_days = array();
		foreach ($lan['LanDay'] as $lan_day) {
			$seats_left = $this->LanSignup->LanSignupDay->LanDay->seatsLeft($lan_day['id']);

			$lan_days[$lan_day['id']] = array(
				'value' => CakeTime::format('D, M jS Y', $lan_day['date']),
				'seats_left' => $seats_left
			);
		}

		$this->set(compact('lan', 'lan_days', 'user'));

	}

}

?>
