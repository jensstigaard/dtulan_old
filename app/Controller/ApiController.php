<?php

class ApiController extends AppController {
	
	public function accesstokens() {
		if(!$this->request->accepts("application/vnd.dtulan+json; version=1.0")) {
			$this->response->statusCode(406);
		} elseif(isset($this->request->data['username']) && isset($this->request->data['password'])) {
			$this->request->data['User']['username'] = $this->request->data['username'];
			$this->request->data['User']['password'] = $this->request->data['password'];
			unset($this->request->data['username']);
			unset($this->request->data['password']);
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		} else {
			$this->response->statusCode(400);
		}
	}
}

?>
