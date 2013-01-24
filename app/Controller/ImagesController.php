<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImagesController
 *
 * @author Jens
 */
class ImagesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Image->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->set('images', $this->Image->getListIndex());
	}

	public function add() {

		if ($this->request->is('post')) {

			$this->request->data['Image']['user_id'] = $this->Image->getLoggedInId();

			if ($this->Image->save($this->request->data)) {
				$this->Session->setFlash('Your image has been saved', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to save image', 'default', array(), 'bad');
			}
		}
	}

}

?>
