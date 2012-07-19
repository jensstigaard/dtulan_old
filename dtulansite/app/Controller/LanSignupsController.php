<?php

/**
 * Description of LanSignupsController
 *
 * @author Jens
 */
class LanSignupsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		return true;
	}

	public function index() {
		$this->set('lan_signups', $this->LanSignup->find('all'));
	}

	public function add() {
		$this->set('lans', $this->LanSignup->Lan->find('list'));
		$this->set('users', $this->LanSignup->User->find('list'));
	}

}

?>
