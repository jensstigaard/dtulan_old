<?php

class PagesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('front', 'view'));
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->Page->isYouAdmin()) {
			return true;
		}

		return false;
	}
	
	public function front(){
		
		$this->set('title_for_layout', 'Home');
		

		$this->set('page', $this->Page->getFrontPage());

		$this->loadModel('Tournament');
		$this->set('tournaments', $this->Tournament->getFrontTournaments());
	}

	public function index() {
		$this->set('pages', $this->Page->getIndexList());
	}

	public function view($slug = 'welcome') {
		$page = $this->Page->getBySlug($slug);

		$title_for_layout = $page['Page']['title'];

		$this->set(compact('page', 'title_for_layout'));
	}

	public function add() {
		if ($this->request->is('post')) {

			$this->request->data['Page']['time_created'] = date('Y-m-d H:i:s');

			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash('Your page has been saved.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your page.', 'default', array(), 'bad');
			}
		}

		$this->set('parents', $this->Page->getPagesToplevel());

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
			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash('Your page has been updated.', 'default', array('class' => 'message success'), 'good');

				$this->Page->read('slug');

				$this->redirect(array('action' => 'view', 'slug' => $this->Page->data['Page']['slug']));
			} else {
				$this->Session->setFlash('Unable to update your page.', 'default', array(), 'bad');
			}
		}

		$parents = $this->Page->getPagesToplevel();

		$this->set(compact('id', 'parents'));
		$this->request->data['Page']['latest_update_by_id'] = $this->Auth->user('id');
	}

}
