<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author Jens
 */
class Page extends AppModel {

	public $belongsTo = array(
		 'LatestUpdateBy' => array(
			  'className' => 'user'
		 ), 'CreatedBy' => array(
			  'className' => 'user'
			  ));
	public $hasMany = array('Underpage' => array(
			  'className' => 'Page',
			  'foreignKey' => 'parent_id',
			  'order' => 'Underpage.sorted ASC',
		 )
	);
	public $validate = array(
		 'title' => array(
			  'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'Title is required'
			  )
		 ),
		 'slug' => array(
			  'rule' => 'isUnique',
			  'message' => 'Page has to be unique'
		 ),
		 'command' => array(
			  'rule' => array('inList', array('text', 'uri')),
			  'message' => 'Invalid command'
		 )
	);

	public function beforeValidate($options = array()) {
		parent::beforeValidate($options);

		if (isset($this->data['Page']['title'])) {
			$this->data['Page']['slug'] = $this->stringToSlug($this->data['Page']['title']);
		}
	}

	public function getPagesToplevel() {

		$opt = $this->find('list', array(
			 'conditions' => array(
				  'parent_id' => 0
			 )
				  ));

		$opt[0] = '[Top level]';

		ksort($opt);

		return $opt;
	}

	public function getBySlug($slug) {
		$page = $this->findBySlug($slug);

		if (!isset($page['Page']['public']) || (!$this->isYouAdmin() && !$page['Page']['public'])) {
			throw new NotFoundException('Page not found');
		}

		$page['Page']['time_latest_update_nice'] = $this->dateToNice($page['Page']['time_latest_update']);

		return $page;
	}

	public function getMenuItems() {
		$pages = $this->find('all', array('conditions' => array(
				  'Page.parent_id' => 0,
				  'Page.public' => 1,
				  'Page.in_menu' => 1,
			 ),
			 'recursive' => 2,
			 'fields' => array(
				  'Page.id',
				  'Page.title',
				  'Page.slug',
				  'Page.public',
				  'Page.in_menu',
				  'Page.parent_id',
				  'Page.command',
				  'Page.command_value',
			 )
				  )
		);

		return $pages;
	}

	/*
	 * Params:
	 * - $page is a array with variable of a given page in, following variables is required in array
	 * 	- command
	 * 	- command_value
	 * 	- title
	 */

	public function getUrl($page) {

		$return = '';

		switch ($page['command']) {
			case 'uri':
				$return = $page['command_value'];
				break;
			default:

				$return = array(
					 'controller' => 'pages',
					 'action' => 'view',
					 'slug' => $this->stringToSlug($page['title'])
				);
				break;
		}

		return $return;
	}

	public function getIndexList() {
		$pages = $this->find('all');

		$this->dateToNiceArray($pages, 'Page', 'time_created');
		$this->dateToNiceArray($pages, 'Page', 'time_latest_update');

		return $pages;
	}

	public function getFrontPage() {
		$page = $this->find('first', array(
						'conditions' => array(
							 'Page.id' => 1
						),
						'contain' => array(
							 'LatestUpdateBy'
						)
				  ));
		
		$page['Page']['time_latest_update'] = $this->dateToNice($page['Page']['time_latest_update']);
		
		return $page;
	}

}

?>
