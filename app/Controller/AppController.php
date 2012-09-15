<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array('controller' => 'pages', 'action' => 'view', 1),
			'logoutRedirect' => array('controller' => 'pages', 'action' => 'view', 1),
			'authError' => 'Access denied',
			'authorize' => array('Controller'), // Added this line
		)
	);

	public function isAuthorized($user) {
		return true;
	}

	public function beforeFilter() {
		// Variables used all over the site - can be accessed in any view
		$is_loggedin = $this->Auth->loggedIn();

		$current_user = $this->Auth->user();

		$is_admin = $is_loggedin && isset($current_user['Admin']['user_id']);

		$this->set(compact('current_user', 'is_loggedin', 'is_admin'));

		
		$this->loadModel('User');

		if ($this->User->LanSignup->Lan->isCurrent($is_admin)) {
			$this->set('sidebar_current_lan', $this->User->LanSignup->Lan->getCurrent($is_admin));
		}



		// For student: Find next lans / For guest: find invites
		if (isset($current_user['type'])) {

			if ($current_user['type'] == 'guest') {
				$this->User->LanInvite->unbindModel(array('belongsTo' => array('Guest', 'LanSignup')));

				$this->set('sidebar_lan_invites', $this->User->LanInvite->find('first', array('conditions' => array(
								'LanInvite.user_guest_id' => $this->Auth->user('id'),
								'LanInvite.accepted' => 0
							)
								)
						)
				);
			} else {

				$this->User->LanSignup->recursive = 0;

				$lans = $this->User->LanSignup->find('all', array('conditions' => array(
						'LanSignup.user_id' => $this->Auth->user('id')
					)
						)
				);

				$lan_ids = array();
				foreach ($lans as $lan) {
					$lan_ids[] = $lan['LanSignup']['lan_id'];
				}

				if ($current_user['type'] == 'student') {
					$this->set('sidebar_next_lan', $this->User->LanSignup->Lan->find('first', array(
								'conditions' => array(
									'Lan.sign_up_open' => 1,
									'Lan.published' => 1,
									'Lan.time_end >' => date('Y-m-d H:i:s'),
									'NOT' => array(
										'Lan.id' => $lan_ids
									)
								),
								'order' => array('Lan.time_start ASC'),
								'recursive' => 0
									)
							)
					);
				}
			}
		}
	}

	public function isAdmin($user = null) {
		if ($user == null && $this->Auth->loggedIn()) {
			$user = $this->Auth->user();
		}

		return isset($user['Admin']['user_id']);
	}

}