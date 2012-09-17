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

	}

	public function api_add() {
		if ($this->request->is('post') && $this->isJsonRequest()) {
			// :-)
			if ($this->Auth->login() && $this->isAdmin()) {
				$key = String::uuid();

				if ($this->AccessToken->save(array(
							'AccessToken' => array(
								'user_id' => $this->Auth->user('id'),
								'key' => $key,
								'time_expiration' => date('Y-m-d H:i:s', strtotime('+1 week'))
							)
								)
						)
				) {
					$this->set('success', true);
					$this->set('data', array(
						'id' => $this->AccessToken->getLatestInsertId(),
						'key' => $key
							)
					);
				} else {
					$this->set('success', false);
					$this->set('data', array(
						'message' => 'Unable to create access token'
							)
					);
				}
			}
		} else {
			$this->set('success', false);
			$this->set('data', array('data' => array(
					'message' => 'Invalid email/password combination'
				)
					)
			);
		}
		$this->set('_serialize', array('success', 'data'));
	}

	public function api_delete() {

	}

}

?>
