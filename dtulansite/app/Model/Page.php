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

	public $hasMany = array('Underpage' => array(
			'className'  => 'Page',
			'foreignKey' => 'parent_id',
			'order'      => 'Underpage.sorted ASC',

		)
	);
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		),
		'command' => array(
			'rule' => '/^(0|1)$/',
			'message' => 'Invalid command'
		)
	);

}

?>
