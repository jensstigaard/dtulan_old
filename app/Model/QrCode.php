<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QrCode
 *
 * @author Jens
 */
class QrCode extends AppModel {

	public $useTable = 'user_qr_codes';
	public $belongsTo = array(
		 'User'
	);

}

?>
