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
	public function accept($id = null){

		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if(!$this->Auth->loggedIn()){
			throw new ForbiddenException('You are not logged in');
		}

		$user = $this->Auth->user();

		$this->LanInvite->id = $id;

		if(!$this->LanInvite->exists()){
			throw new NotFoundException('Lan invite not found');
		}

		$lan_invite = $this->LanInvite->read();

		if(!$lan_invite['Guest']['id'] == $user['id']){
			throw new ForbiddenException('You are not allowed to accept this invite');
		}


		$this->request->data['LanInvite']['accepted'] = 1;

		if($this->LanInvite->save($this->request->data)){
			$this->Session->setFlash('Invite accepted');
			$this->redirect(array('controller' => 'lan_signups', 'action' => 'add', $lan_invite['Lan']['id']));
		}
	}
}

?>
