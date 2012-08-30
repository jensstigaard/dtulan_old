<?php

class ApiController extends AppController {
	
	public function beforeFilter() {
		$this->Auth->allow('accesstokens');
		parent::beforeFilter();
	}
	
	public function accesstokens() {
		if(!$this->request->accepts("application/json")) {
			$this->response->statusCode(406);
		} elseif(isset($this->request->data['username']) && isset($this->request->data['password'])) {
			$this->request->data['User']['username'] = $this->request->data['username'];
			$this->request->data['User']['password'] = $this->request->data['password'];
			unset($this->request->data['username']);
			unset($this->request->data['password']);
			
			if($this->request->is('post') && $this->request->accepts("application/json")) {
				$this->response->header(array('content-type: application/json'));
				$this->render('API/response');
				$data = array();
				if($this->Auth->login()) {
					$this->set('succes', 'true');
					$this->set($data, array('access_token' => $this->Auth->user('access_token')));
				} else {
					$this->set('succes', 'false');
					$this->set($data, array('message' => _('Invalid email or password!')));
				}
			}
		} else {
			$this->response->statusCode(400);
		}
	}
}

?>
