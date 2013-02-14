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
class NewsItemsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('view', 'front'));
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->request->prefix == 'crew' && !$this->NewsItem->isYouAdmin()) {
			return false;
		}

		return true;
	}

	public function crew_index() {

		$this->set('news', $this->NewsItem->find('all'));
	}

	public function crew_add() {
		if ($this->request->is('post')) {

			$this->request->data['NewsItem']['date'] = date('Y-m-d');
			$this->request->data['NewsItem']['time_created'] = date('Y-m-d H:i:s');

			if ($this->NewsItem->save($this->request->data)) {
				$this->Session->setFlash('News has been saved', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to save news', 'default', array(), 'bad');
			}
		}
	}

	public function crew_edit($id) {

		$this->view = 'crew_add';

		$this->NewsItem->id = $id;

		if (!$this->NewsItem->exists()) {
			throw new NotFoundException('News not found');
		}

		if ($this->request->is('get')) {
			$this->request->data = $this->NewsItem->read();
		} else {
			if ($this->NewsItem->save($this->request->data)) {
				$this->Session->setFlash('News has been saved', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'view'));
			} else {
				$this->Session->setFlash('Unable to save news', 'default', array(), 'bad');
			}
		}
	}

	public function front() {
		$this->set('latest_news', $this->NewsItem->getLatestNews());

		$this->loadModel('Page');
		$this->set('page', $this->Page->getFrontPage());

		$this->loadModel('Tournament');
		$this->set('tournaments', $this->Tournament->getFrontTournaments());
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
