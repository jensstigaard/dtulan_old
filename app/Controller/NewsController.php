<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewsController
 *
 * @author Jens
 */
class NewsController extends AppController {
	
	public $uses = 'News';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->request->prefix == 'crew' && !$this->News->isYouAdmin()) {
			return false;
		}

		return true;
	}

	public function crew_add() {
		if ($this->request->is('post')) {
			if ($this->News->save($this->reqeust->data)) {
				$this->Session->setFlash('News has been saved', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'view'));
			} else {
				$this->Session->setFlash('Unable to save news', 'default', array(), 'bad');
			}
		}
	}

	public function crew_edit($id) {

		$this->News->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException('News not found');
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->News->read();
		} else {
			if ($this->News->save($this->reqeust->data)) {
				$this->Session->setFlash('News has been saved', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'view'));
			} else {
				$this->Session->setFlash('Unable to save news', 'default', array(), 'bad');
			}
		}
	}

	public function view($date, $slug) {

		$this->News->id = $this->News->getIdByDateAndSlug($date, $slug);

		$this->set('news', $this->News->find('first', array(
						'conditions' => array(
							 'News.id' => $this->News->id
						)
				  )));
	}

}

?>
