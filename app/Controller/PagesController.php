<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Pages';

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Form', 'Js', 'Fck');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view', 'menu', 'menuItem');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)) {
			return true;
		}

		return false;
	}

	public function index() {
		$this->set('pages', $this->Page->find('all'));
	}

	public function view($slug = 'welcome') {
		$page = $this->Page->findBySlug($slug);

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

	public function menu() {
		$this->Page->recursive = 2;
		$pages = $this->Page->find('all', array('conditions' => array(
						'Page.parent_id' => 0,
						'Page.public' => 1
					),
			'fields' => array(
				'Page.id',
				'Page.title',
				'Page.slug',
				'Page.public',
				'Page.parent_id',
				'Page.command',
				'Page.command_value',
			)
						)
		);

//		debug($pages);

		return $pages;
	}

}
