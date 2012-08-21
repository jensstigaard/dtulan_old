<?php

/**
 * Description of Payment
 *
 * @author superkatten
 */
class Payment extends AppModel {
	public $belongsTo = array('User');

	public $hasOne = array('Crew');
}

?>
