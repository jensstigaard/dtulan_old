<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignupCode
 *
 * @author Jens
 */
class LanSignupCode extends AppModel {

	public $belongsTo = array(
		 'Lan', 'LanSignup'
	);

	public function isNotUsed($code) {
		return $this->find('count', array(
						'conditions' => array(
							 'accepted' => 0,
							 'code' => $code,
							 'lan_id' => $this->Lan->id
						),
						'recursive' => -1
				  )) == 1;
	}

	public function getIdByCode($code) {
		$result = $this->find('first', array(
			 'conditions' => array(
				  'code' => $code
			 ),
			 'fields' => array(
				  'id'
			 )
				  ));

		if (!isset($result['LanSignupCode']['id'])) {
			return -1;
		}

		return $result['LanSignupCode']['id'];
	}

	/*
	 * 
	 * Reset code for LanSignupCode 
	 */

	public function resetCodeByLanSignupId($lan_signup_id) {
		$code = $this->find('first', array(
			 'conditions' => array(
				  'lan_signup_id' => $lan_signup_id
			 )
				  ));

		if ($code) {
			$this->id = $code['LanSignupCode']['id'];
			$reset['LanSignupCode'] = array(
				 'accepted' => 0,
				 'lan_signup_id' => 0
			);

			if ($this->save($reset)) {
				return true;
			} else {
				return false;
			}
		}

		return true;
	}

}

?>
