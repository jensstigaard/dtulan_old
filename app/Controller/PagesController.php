<?php

class PagesController extends AppController {

	public $helpers = array('Html', 'Form', 'Js');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Page->isYouAdmin()) {
			return true;
		}

		return false;
	}

	public function index() {

		$pages = $this->Page->find('all');

		$this->Page->dateToNiceArray($pages, 'Page', 'time_created');
		$this->Page->dateToNiceArray($pages, 'Page', 'time_latest_update');

		$this->set(compact('pages'));
	}

	public function view($slug = 'welcome') {
		$page = $this->Page->findBySlug($slug);

		if (!isset($page['Page']['public']) || (!$this->Page->isYouAdmin() && !$page['Page']['public'])) {
			throw new NotFoundException('Page not found');
		}

		$page['Page']['time_latest_update_nice'] = $this->Page->dateTonice($page['Page']['time_latest_update']);

		$title_for_layout = $page['Page']['title'];

		$this->set(compact('page', 'title_for_layout'));
	}

	public function add() {
		if ($this->request->is('post')) {

			$this->request->data['Page']['time_created'] = date('Y-m-d H:i:s');
			$this->request->data['Page']['slug'] = $this->Page->stringToSlug($this->request->data['Page']['title']);

			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash('Your page has been saved.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your page.', 'default', array(), 'bad');
			}
		}

		$opt = $this->Page->find('list', array('conditions' => array('parent_id' => 0)));
		$opt[0] = '[Top level]';
		ksort($opt);

		$this->set('parents', $opt);

		$this->request->data['Page']['created_by_id'] = $this->request->data['Page']['latest_update_by_id'] = $this->Auth->user('id');
	}

	public function edit($id = null) {
		$this->Page->id = $id;

		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Page not found'));
		}

		// If get-request is set, then read page
		if ($this->request->is('get')) {
			$this->request->data = $this->Page->read();
		} else {
			// Otherwise - save the page

			$slug = $this->request->data['Page']['slug'] = $this->Page->stringToSlug($this->request->data['Page']['title']);

			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash('Your page has been updated.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'view', 'slug' => $slug));
			} else {
				$this->Session->setFlash('Unable to update your page.', 'default', array(), 'bad');
			}
		}

		$opt = $this->Page->find('list', array('conditions' => array('id <>' => $id, 'parent_id' => 0)));
		$opt[0] = '[Top level]';
		ksort($opt);

		$parents = $opt;

		$this->set(compact('id', 'parents'));
		$this->request->data['Page']['latest_update_by_id'] = $this->Auth->user('id');
	}

}
