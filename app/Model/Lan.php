<?php

class Lan extends AppModel {

	public $name = 'Lan';
	public $hasMany = array('LanSignup', 'LanDay', 'Tournament');
	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Title is required'
			)
		),
		'max_participants' => array(
			'required' => array(
				'rule' => array('numeric'),
				'message' => 'Has to be a number'
			)
		),
		'max_guests_per_student' => array(
			'required' => array(
				'rule' => array('numeric'),
				'message' => 'Has to be a number'
			)
		),
		'published' => array(
			'required' => array(
				'rule' => array('between', 0, 1),
				'message' => 'Valid publishing required'
			)
		),
		'sign_up_open' => array(
			'required' => array(
				'rule' => array('between', 0, 1),
				'message' => 'Valid Sign Up Open required'
			)
		),
		'time_start' => array(
			'bigger than end' => array(
				'rule' => 'validateDates',
				'message' => 'Invalid start-/end-time',
			)
		)


	);

	public function validateDates($check){
		if($check['time_start'] >= $this->data['Lan']['time_end']){
			$this->invalidate('time_end', 'Invalid start-/end-time');
			return false;
		}
		return true;
	}

	public function getLanDays($start, $end){
		App::uses('CakeTime', 'Utility');

		$date_start = $start['year'].'-'.$start['month'].'-'.$start['day'];
		$date_end = $end['year'].'-'.$end['month'].'-'.$end['day'];

		$days = array();

		$date_current = $date_start;
		while($date_current <= $date_end){
			$days[] = array('date' => $date_current);

			$date_current = CakeTime::format('Y-m-d', strtotime('+1 day', strtotime($date_current)));
		}

		return $days;
	}
}

?>
