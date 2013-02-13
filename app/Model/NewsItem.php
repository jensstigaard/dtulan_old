<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of News
 *
 * @author Jens
 */
class NewsItem extends AppModel {

	public $order = array(
		 'time_created' => 'desc'
	);
	public $validate = array(
		 'title' => array(
			  'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Title cannot be empty'
			  )
		 ),
		 'slug' => array(
			  'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Text cannot be empty'
			  )
		 ),
		 'text' => array(
			  'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Text cannot be empty'
			  )
		 ),
	);

	public function beforeValidate($options = array()) {
		parent::beforeValidate($options);

		if (isset($this->data['NewsItem']['title'])) {
			$this->data['NewsItem']['slug'] = $this->stringToSlug($this->data['NewsItem']['title']);
		}
	}

	public function getIdByDateAndSlug($date, $slug) {

		$news = $this->find('first', array(
			 'conditions' => array(
				  'time_created LIKE' => $date . '%',
				  'slug' => $slug
			 ),
			 'fields' => array(
				  'News.id'
			 )
				  ));

		if (!isset($news['News']['id'])) {
			throw new NotFoundException('News not found.');
		}

		return $news['News']['id'];
	}

}

?>
