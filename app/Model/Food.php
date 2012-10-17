<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SweetModel
 *
 * @author Jens
 */
class Food extends AppModel {

	public $order = array(
		'available' => 'desc',
		'sorted' => 'asc'
	);
}

?>
