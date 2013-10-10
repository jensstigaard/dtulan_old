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

	public $helpers = array(
		 'Html',
		 'Form',
		 'Js',
		 'Chosen.Chosen',
	);
	public $components = array(
		 'RequestHandler',
		 'Session',
		 'Auth' => array(
			  'loginRedirect' => '/',
			  'logoutRedirect' => '/',
			  'authError' => 'Access denied',
			  'authorize' => array('Controller'), // What does this do??
			  'authenticate' => array(
					// Allow authentication by user / password
					'Form' => array(
						 'userModel' => 'User',
						 'fields' => array(
							  'username' => 'email',
							  'password' => 'password'
						 ),
						 'scope' => array(
							  'User.activated' => 1,
//							  'Admin.id >' => 0 // Only admin possible to log in
						 )
					),
			  // Allow authentication by access token
//					'Api' => array(
//						 'userModel' => 'AccessToken',
//						 'fields' => array(
//							  'username' => 'id',
//						 ),
//					),
			  )
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

		$this->loadModel('Lan');

		$this->set('lans_highlighted', $this->Lan->getHighlighted());

		if ($is_loggedin) {
			$this->Lan->LanSignup->User->id = $current_user['id'];
			$this->Lan->LanSignup->User->read(array('balance'));
			$this->set('current_user_balance', $this->Lan->LanSignup->User->data['User']['balance']);
			$this->set('sidebar_new_lan', $this->Lan->LanSignup->User->getNewLans());
			$this->set('sidebar_team_invites', $this->Lan->LanSignup->User->getTournamentTeamInvites());
		}
	}

	public function isJsonRequest() {
		return $this->request->header('Accept') === 'application/json';
	}

}
