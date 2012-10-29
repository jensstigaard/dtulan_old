<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	public function dateToNiceArray(&$array, $model_name = null, $field_name_time = 'time', $with_time = true) {
		foreach ($array as $index => $content) {
			if ($model_name == null && isset($content[$field_name_time])) {
				$array[$index][$field_name_time . '_nice'] = $this->dateToNice($content[$field_name_time]);
			} else {
				$array[$index][$model_name][$field_name_time . '_nice'] = $this->dateToNice($content[$model_name][$field_name_time], $with_time);
			}
		}
	}

	public function dateToNice($timestamp, $with_time = true) {

		App::uses('CakeTime', 'Utility');

		$return = '';

		if (CakeTime::isToday($timestamp)) {
			$return .= 'Today';
		} elseif (CakeTime::isTomorrow($timestamp)) {
			$return .= 'Tomorrow';
		} elseif (CakeTime::wasYesterday($timestamp)) {
			$return .= 'Yesterday';
		} elseif (CakeTime::isThisWeek($timestamp)) {
			$return .= CakeTime::format('l', $timestamp);
		} else {
			$return .= CakeTime::format('D, M jS', $timestamp);
		}

		if ($with_time) {
			$return .= ' ';
			$return .= CakeTime::format('H:i', $timestamp);
		}


		return $return;
	}

}
