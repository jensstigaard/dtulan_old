<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GamesController
 *
 * @author Jens
 */
class GamesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Game->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function index() {
		$this->set('games', $this->Game->getListIndex());
	}

	public function add() {

		if ($this->request->is('post')) {

//			$this->request->data['Image']['user_id'] = $this->Image->getLoggedInId();

			if ($this->Game->save($this->request->data)) {
				$this->Session->setFlash('Your game has been saved', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to save game', 'default', array(), 'bad');
			}
		}

		$this->set('images', $this->Game->Image->find('list'));
	}

	public function edit($id) {
		$this->Game->id = $id;

		if (!$this->Game->exists()) {
			throw new NotFoundException('Game not found :(');
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->Game->read();
		} else {
			if ($this->Game->save($this->request->data)) {
				$this->Session->setFlash('Your game has been saved', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to save game', 'default', array(), 'bad');
			}
		}

		$this->set('images', $this->Game->Image->find('list'));
	}

}

?>
