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
					'fields' => array(),
					'thumbnailSizes' => array(
						 'thumb_60h' => '60h',
						 'thumb_100h' => '100h',
						 'thumb_210w' => '210w',
					)
			  )
		 )
	);
	public $belongsTo = array(
		 'User'
	);
	public $hasOne = array(
		 'Games'
	);
	public $validate = array(
		 'title' => array(
			  'not empty' => array(
					'rule' => 'notEmpty',
					'message' => 'Title cannot be empty'
			  )
		 ),
		 'file' => array(
			  'rule1' => array(
					'rule' => 'isUnderPhpSizeLimit',
					'message' => 'File exceeds upload filesize limit'
			  ),
			  'rule 2' => array(
					'rule' => 'isUnderFormSizeLimit',
					'message' => 'File exceeds form upload filesize limit'
			  ),
			  'rule 3' => array(
					'rule' => 'isCompletedUpload',
					'message' => 'File was not successfully uploaded'
			  ),
			  'rule 4' => array(
					'rule' => 'isFileUpload',
					'message' => 'File was missing from submission'
			  ),
			  'rule 5' => array(
					'rule' => 'tempDirExists',
					'message' => 'The system temporary directory is missing'
			  ),
			  'rule 6' => array(
					'rule' => 'isSuccessfulWrite',
					'message' => 'File was unsuccessfully written to the server'
			  ),
			  'rule 7' => array(
					'rule' => 'noPhpExtensionErrors',
					'message' => 'File was not uploaded because of a faulty PHP extension'
			  ),
			  'rule 8' => array(
					'rule' => array('isValidMimeType', array('image/png', 'image/gif', 'image/jpeg')), // 'application/pdf',
					'message' => 'File is not a png, gif or jpeg'
			  ),
			  'rule 9' => array(
					'rule' => array('isWritable'),
					'message' => 'File upload directory was not writable'
			  ),
			  'rule 10' => array(
					'rule' => array('isValidDir'),
					'message' => 'File upload directory does not exist'
			  ),
			  'rule 11' => array(
					'rule' => array('isBelowMaxSize', 10485760), // 1024*1024*10 = 10 MB
					'message' => 'File is larger than the maximum filesize'
			  ),
			  'rule 12' => array(
					'rule' => array('isAboveMinSize', 1024), // 1024 * 1 = 1 KB
					'message' => 'File is below the mimimum filesize'
			  ),
			  'rule 13' => array(
					'rule' => array('isValidExtension', array('jpg', 'png', 'gif')),
					'message' => 'File does not have a jpg, png, or gif extension'
			  ),
			  'rule 14' => array(
					'rule' => array('isAboveMinHeight', 50),
					'message' => 'File is below the minimum height'
			  ),
			  'rule 15' => array(
					'rule' => array('isBelowMaxHeight', 1500),
					'message' => 'File is above the maximum height'
			  ),
			  'rule 16' => array(
					'rule' => array('isAboveMinWidth', 120),
					'message' => 'File is below the minimum width'
			  ),
			  'rule 17' => array(
					'rule' => array('isBelowMaxWidth', 3000, false),
					'message' => 'File is above the maximum width'
			  ),
		 )
	);

	public function getListIndex() {
		$images = $this->find('all', array(
			 'recursive' => -1
				  ));

		foreach ($images as $key => $value) {
			$images[$key]['Image']['fileName'] = $this->getFileName($value['Image']);
			$images[$key]['Image']['fileSize'] = $this->formatBytes($value['Image']['size']);
		}

		return $images;
	}

	public function getFileName($fields) {
		return $fields['id'] . '.' . $fields['ext'];
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
