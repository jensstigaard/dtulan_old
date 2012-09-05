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
//		$this->Auth->allow('');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);
	}

}

?>
