<?php

/**
 * Description of Payment
 *
 * @author superkatten
 */
class Payment extends AppModel {

	public $belongsTo = array('User');
	public $validate = array(
		'amount' => array(
			'required1' => array(
				'rule' => array('notEmpty', 'numeric'),
				'message' => 'Amount is invalid'
			),
			'required2' => array(
				'rule' => 'positiveInteger',
				'message' => 'Amount is invalid'
			),
		),
		'user_id' => array(
			'validUser' => array(
				'rule' => 'validUser',
				'message' => 'Invalid user'
			)
		),
		'crew_id' => array(

		)
	);

	public function positiveInteger($check) {
		if (is_numeric($check['amount']) && $check['amount'] > 0 && $check['amount'] <= 999) {
			return true;
		} else {
			return false;
		}
	}

	public function validUser($check) {
		return $this->User->find('count', array('conditions' => array(
						'User.id' => $check['user_id'],
						'User.activated' => 1
					)
						)
		) == 1;
	}

}

?>
