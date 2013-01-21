<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccessTokensController
 *
 * @author Jens
 */
class AccessTokensController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('api_add');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);
	}

	public function api_add() {
		if ($this->request->is('post')) {
			if ($this->isJsonRequest()) {
				if ($this->Auth->login()) {
					$data = array();
					$data['AccessToken']['user_id'] = $this->Auth->user('id');
					$data['AccessToken']['time_expiration'] = date('Y-m-d H:i:s', strtotime('+1 week'));
					if ($this->AccessToken->save($data)) {
						$this->set('success', true);
						$this->set('data', array(
							 'access_token' => $this->AccessToken->getLastInsertId(),
								  )
						);
					} else {
						$this->set('success', false);
						$this->set('data', array(
							 'message' => __('Unable to create access token')
								  )
						);
					}
				} else {
					$this->set('success', false);
					$this->set('data', array(
						 'message' => __('Invalid email and password combination')
							  )
					);
				}
				$this->set('_serialize', array('success', 'data'));
			} else {
				throw new BadRequestException('Invalid request from client');
			}
		} else {
			throw new MethodNotAllowedException('Not allowed');
		}
	}

	public function api_delete() {
		if ($this->request->is('delete')) {
			if ($this->isJsonRequest()) {
				if ($this->Auth->login() && $this->isAdmin()) {
					if ($this->AccessToken->updateAll()) {
						$this->set('success', true);
						$this->set('data', array('message' => 'Successfully logout'));
					} else {
						$this->set('success', false);
						$this->set('data', array('message' => __('Unable to logout')));
					}
					$this->set('_serialize', array('data', 'success'));
				} else {
					$this->response->statusCode(401);
				}
			} else {
				$this->response->statusCode(406);
			}
		} else {
			$this->response->statusCode(405);
		}
	}

}

?>
