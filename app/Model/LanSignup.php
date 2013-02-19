<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignup
 *
 * @author Jens
 */
class LanSignup extends AppModel {

	public $belongsTo = array(
		 'User',
		 'Lan'
	);
	public $hasOne = array(
		 'LanSignupCode'
	);
	public $validate = array(
		 'user_id' => array(
			  'validateUser' => array(
					'rule' => 'validateUser',
					'message' => 'Invalid user'
			  ),
			  'checkNotInLan' => array(
					'rule' => 'validateUserInLan',
					'message' => 'Invalid signup'
			  )
		 ),
		 'lan_id' => array(
			  'valid' => array(
					'rule' => 'validateLan',
					'message' => 'Invalid lan'
			  )
		 ),
		 'code' => array(
			  'validCode' => array(
					'rule' => 'validateCode',
					'message' => 'Invalide code'
			  )
		 ),
	);

	public function validateUserInLan($check) {
		if ($this->find('count', array('conditions' => array(
							 'LanSignup.user_id' => $check['user_id'],
							 'LanSignup.lan_id' => $this->data['LanSignup']['lan_id'],
						)
							 )
				  )
				  == 0) {
			return true;
		}

		return false;
	}

	public function validateUser($check) {
		if ($this->User->find('count', array(
						'conditions' => array(
							 'User.id' => $check['user_id']
						)
							 )
				  )
				  == 1) {
			return true;
		}

		return false;
	}

	public function validateLan($check) {
		$this->Lan->id = $check['lan_id'];

		if ($this->Lan->isSignupPossible()) {
			return true;
		}

		return false;
	}

	public function validateCode($check) {
		$this->Lan->id = $this->Lan->data['Lan']['id'];

		if (!$this->Lan->exists()) {
			throw new NotFoundException('Lan not found.');
		}

		$this->Lan->read(array('need_physical_code'));

		if (!$this->Lan->data['Lan']['need_physical_code']) {
			return true;
		} else {
			return $this->Lan->LanSignupCode->isNotUsed($check['code']);
		}
	}

	public function getDataForDeletion() {

		$result = $this->find('first', array(
			 'conditions' => array(
				  'LanSignup.lan_id' => $this->Lan->id,
				  'LanSignup.user_id' => $this->User->id
			 ),
			 'fields' => array(
				  'LanSignup.id',
				  'Lan.price',
				  'Lan.need_physical_code'
			 )
				  )
		);

		$this->id = $result['LanSignup']['id'];

		if (!$this->exists()) {
			throw new NotFoundException('Lan Signup not found');
		}

		return $result;
	}

	/*
	 * 
	 * Delete LanSignup by User.id and Lan.id
	 * 
	 * Required
	 * 	- $user_id
	 * 	- $lan_id
	 */

	public function deleteByUserIdAndLanId($user_id, $lan_id) {
		$this->User->id = $user_id;
		$this->Lan->id = $lan_id;

		if (!$this->Lan->isSignupOpen()) {
			throw new UnauthorizedException('Unable to delete signup when signup is not open.');
		}

		$user = $this->User->read(array('id', 'balance'));

		$lan_signup = $this->getDataForDeletion();

		$this->id = $lan_signup['LanSignup']['id'];

		if (!$lan_signup['Lan']['need_physical_code']) {
			$new_balance = $user['User']['balance'] + $lan_signup['Lan']['price'];
		} else {
			$new_balance = $user['User']['balance'];
		}


		$this->Lan->read(array('slug'));

		$dataSource = $this->getDataSource();
		$dataSource->begin();
		if (
				  $this->User->saveField('balance', $new_balance, true)
				  &&
				  $this->delete()
				  &&
				  $this->LanSignupCode->resetCodeByLanSignupId($lan_signup['LanSignup']['id'])
		) {
			$dataSource->commit();
			return true;
		} else {
			$dataSource->rollback();
			return false;
		}
	}

}

?>
