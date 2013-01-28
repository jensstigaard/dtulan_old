<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserPasswordTicket
 *
 * @author Jens
 */
class UserPasswordTicket extends AppModel {

	public $validate = array(
		 'user_id' => array(
			  'is unique' => array(
					'rule' => 'isUnique',
					'message' => 'User already got a ticket'
			  )
		 )
	);

}

?>
