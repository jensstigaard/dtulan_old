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


	public function getUserIDsAdmins() {
		$this->recursive = -1;
		$admins = $this->find('all');

		$admin_user_ids = array();
		foreach ($admins as $admin) {
			$admin_user_ids[] = $admin['Admin']['user_id'];
		}

		return $admin_user_ids;
	}

}

?>
