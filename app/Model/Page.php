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

	public function stringToSlug($str) {
		// turn into slug
		$str = Inflector::slug($str);
		// to lowercase
		$str = strtolower($str);
		return $str;
	}

}

?>
