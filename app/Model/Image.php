<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author Jens
 */
class Image extends AppModel {

	public $actsAs = array(
		 'Upload.Upload' => array(
			  'file' => array(
					'pathMethod' => 'flat',
					'fields' => array(
						 'dir' => 'image_dir'
					),
					'thumbnailSizes' => array(
//						 'xvga' => '1024x768',
//						 'vga' => '640x480',
						 'thumb' => '80x80'
					)
			  )
		 )
	);
	public $validate = array(
		 'title' => array(
			  'not empty' => array(
					'rule' => 'notEmpty',
					'message' => 'Title cannot be empty'
			  )
		 )
	);

}

?>
