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
class News extends AppModel {
	
	public $useTable = 'news';
	
	public $validate = array(
		 
	);
	
	public function getIdByDateAndSlug($date, $slug){
		
		$news = $this->find('first', array(
			 'conditions' => array(
				  'time_created LIKE' => $date.'%',
				  'slug' => $slug
			 ),
			 'fields' => array(
				  'News.id'
			 )
		));
		
		if(!isset($news['News']['id'])){
			throw new NotFoundException('News not found.');
		}
		
		return $news['News']['id'];
	}
	
}

?>
