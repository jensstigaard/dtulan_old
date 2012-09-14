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
		$current_user = $this->Auth->user();
		$this->set('logged_in', $this->Auth->loggedIn());
		$this->set('is_admin', isset($current_user['Admin']['user_id']));
		$this->set(compact('current_user'));


		if (isset($current_user['type'])) {

			$user_id = $current_user['id'];

			$this->loadModel('User');


			if ($current_user['type'] == 'guest') {
				$this->User->LanInvite->unbindModel(array('belongsTo' => array('Guest', 'LanSignup')));

				$lan_invites = $this->User->LanInvite->find('first', array('conditions' => array(
						'LanInvite.user_guest_id' => $user_id,
						'LanInvite.accepted' => 0
					)
						)
				);
				$this->set(compact('lan_invites'));
			} else {

				$this->User->LanSignup->recursive = 0;

				$lans = $this->User->LanSignup->find('all', array('conditions' => array(
						'LanSignup.user_id' => $user_id
					)
						)
				);

				$lan_ids = array();
				foreach ($lans as $lan) {
					$lan_ids[] = $lan['LanSignup']['lan_id'];
				}

				if ($current_user['type'] == 'student') {
					$this->set('next_lan', $this->User->LanSignup->Lan->find('first', array(
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
		if($user == null && $this->Auth->loggedIn()){
			$user = $this->Auth->user();
		}

		return isset($user['Admin']['user_id']);
	}

}