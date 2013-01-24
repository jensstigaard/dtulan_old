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
					'path' => '{ROOT}webroot{DS}img{DS}uploads{DS}',
					'fields' => array(
					),
					'thumbnailSizes' => array(
						 'thumb_60h' => '60h',
						 'thumb_100h' => '100h',
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

	public function getListIndex() {
		$images = $this->find('all', array(
			 'recursive' => -1
				  ));

		foreach ($images as $key => $value) {
			$images[$key]['Image']['fileName'] = $this->getFileName($value);
			$images[$key]['Image']['fileSize'] = $this->formatBytes($value['Image']['size']);
		}

		return $images;
	}

	public function getFileName($fields) {
		return $fields['Image']['id'] . '.' . $fields['Image']['ext'];
	}

	function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		$bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow)); 

		return round($bytes, $precision) . ' ' . $units[$pow];
	}

}

?>
