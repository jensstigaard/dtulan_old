<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Game
 *
 * @author Superkatten
 */
class Game extends AppModel {

	public $belongsTo = array(
		 'Image'
	);
	public $hasMany = array(
		 'Tournament'
	);
	public $validate = array(
		 'title' => array(
			  'isUnique' => array(
					'rule' => 'isUnique',
					'message' => 'A game with this title does already exists'
			  )
		 )
	);

	public function getListIndex() {
		return $this->find('all', array(
						'recursive' => 0
				  ));
	}

}

?>
