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

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function add($lan_slug) {

		App::uses('CakeTime', 'Utility');

		$this->LanSignup->User->id = $this->Auth->user('id');

		if (!$this->LanSignup->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$user = $this->LanSignup->User->read(array('id', 'balance'));

		$this->LanSignup->Lan->id = $this->LanSignup->Lan->getIdBySlug($lan_slug);

		if ($this->LanSignup->Lan->isUserAttending()) {
			throw new BadRequestException(__('LAN already signed up'));
		}
		
		$lan = $this->LanSignup->Lan->read(array(
			 'title',
			 'need_physical_code',
			 'price',
			 'time_start',
			 'time_end'
		));

		if ($this->request->is('post')) {
			$this->request->data['LanSignup']['lan_id'] = $this->LanSignup->Lan->id;

			$this->request->data['User']['id'] = $user['User']['id'];
			$this->request->data['User']['balance'] = $user['User']['balance'] - $lan['Lan']['price'];

			$this->request->data['LanSignupCode'] = array(
				 'id' => $this->LanSignup->LanSignupCode->getIdByCode($this->request->data['LanSignup']['code']),
				 'accepted' => 1,
			);

			unset($this->request->data['LanSignup']['code']);

			if ($this->LanSignup->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your signup has been saved', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('controller' => 'lans', 'action' => 'view', $lan_slug));
			} else {
				$this->Session->setFlash('Unable to add your signup', 'default', array(), 'bad');
			}
		}

		$this->set('lan', $lan);
	}

	public function delete($lan_slug) {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Bad request from client');
		}

		if ($this->LanSignup->deleteByUserIdAndLanId($this->Auth->user('id'), $this->LanSignup->Lan->getIdBySlug($lan_slug))) {
			$this->Session->setFlash('Your signup has been deleted', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Your signup could not be deleted', 'default', array(), 'bad');
		}


		$this->redirect($this->referer());
	}

}

?>
