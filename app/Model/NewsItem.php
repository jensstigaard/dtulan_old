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
		 'text' => array(
			  'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Text cannot be empty'
			  )
		 ),
	);

	public function beforeValidate($options = array()) {
		parent::beforeValidate($options);
	}

	public function getLatestNews() {
		$latest_news = $this->find('all', array(
			 'limit' => 3
				  ));

		foreach ($latest_news as $x => $content) {
			$latest_news[$x]['NewsItem']['time_created'] = $this->dateToNice($content['NewsItem']['time_created']);
		}

		return $latest_news;
	}

}

?>
