<?php

/**
 * Description of Admin
 *
 * @author Jens
 */
class Admin extends AppModel {

	public $belongsTo = array(
		'User'
	);

	public $validate = array(
		'user_id' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Unique user'
			)
		)
	);

}

?>
