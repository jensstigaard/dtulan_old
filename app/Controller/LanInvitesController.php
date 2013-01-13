<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanInvitesController
 *
 * @author Jens
 */
class LanInvitesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);
		return true;
	}

	public function decline($id = null) {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Invalid request from client');
		}

		$this->LanInvite->id = $id;

		if (!$this->LanInvite->exists()) {
			throw new NotFoundException('LanSignup Not found');
		}

		$this->LanInvite->read();

		$user_logged_in = $this->Auth->user();

		if (!$user_logged_in['id'] == $this->LanInvite->data['LanInvite']['user_guest_id']) {
			throw new UnauthorizedException('You are not allowed to decline this invite');
		}

		if ($this->LanInvite->delete()) {
			$this->Session->setFlash('Your invite has been declined', 'default', array('class' => 'message success'), 'good');
			$this->redirect(array('controller' => 'users', 'action' => 'profile'));
		}
		else{
			$this->Session->setFlash('Unable to decline invite. Please try again', 'default', array(), 'bad');
			$this->redirect($this->referer());
		}
	}

}

?>
