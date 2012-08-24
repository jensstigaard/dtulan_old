<?php

class PizzaWave extends AppModel {

        public $name = 'PizzaWave';
	public $hasMany = 'Order';
	public $belongsTo = 'Lan';
	
	public $validate = array(
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

}



?>
