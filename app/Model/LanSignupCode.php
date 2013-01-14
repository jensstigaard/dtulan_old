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
	
	public function getIdByCode($code){
		$result = $this->find('first', array(
			'conditions' => array(
				'code' => $code
			),
			'fields' => array(
				'id'
			)
		));
		
		return $result['LanSignupCode']['id'];
	}

}

?>
