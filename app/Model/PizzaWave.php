<?php

class PizzaWave extends AppModel {

	public $hasMany = array('PizzaOrder' => array('foreignKey' => 'pizza_wave_id'));
	public $belongsTo = array('Lan');
	public $validate = array(
		'time_start' => array(
			'bigger than end' => array(
				'rule' => 'validateDates',
				'message' => 'Invalid start-/end-time',
			),
			'validInterval' => array(
				'rule' => 'validInterval',
				'message' => 'There already exist a pizza wave in this interval'
			)
		)
	);

	public function validateDates($check) {
		if ($check['time_start'] >= $this->data['PizzaWave']['time_end']) {
			$this->invalidate('time_end', 'Invalid start-/end-time');
			return false;
		}
		return true;
	}

	public function validInterval($check) {
		return $this->find('count', array(
					'conditions' => array(
						'OR' => array(
							array(
								'PizzaWave.time_start >= ' => $this->data['PizzaWave']['time_start'],
								'PizzaWave.time_start < ' => $this->data['PizzaWave']['time_end'],
							),
							array(
								'PizzaWave.time_end > ' => $this->data['PizzaWave']['time_start'],
								'PizzaWave.time_end <= ' => $this->data['PizzaWave']['time_end'],
							),
							array(
								'PizzaWave.time_start <= ' => $this->data['PizzaWave']['time_start'],
								'PizzaWave.time_end >= ' => $this->data['PizzaWave']['time_end'],
							)
						)
					)
						)
				) == 0;
	}

	public function isOnAir() {
		$current_time = date('Y-m-d H:i:s');

		$current_wave = $this->Lan->PizzaWave->find('count', array(
			'conditions' => array(
				'PizzaWave.time_end >' => $current_time,
				'PizzaWave.time_start <' => $current_time
			)
				)
		);

		return $current_wave;
	}

	public function getOnAir($lan_id = null) {
		$current_time = date('Y-m-d H:i:s');

		$conditions = array(
			'PizzaWave.time_end >' => $current_time,
			'PizzaWave.time_start <' => $current_time
		);

		if ($lan_id != null) {
			$conditions['PizzaWave.lan_id'] = $lan_id;
		}

		$current_wave = $this->Lan->PizzaWave->find('first', array(
			'conditions' => $conditions,
			'order' => array(
				'PizzaWave.time_start ASC'
			)
				)
		);

		return $current_wave;
	}

	public function getAvailable($lan_id = null) {
		$current_time = date('Y-m-d H:i:s');

		$conditions = array(
			'PizzaWave.time_end >' => $current_time,
		);

		if ($lan_id != null) {
			$conditions['PizzaWave.lan_id'] = $lan_id;
		}

		$current_wave = $this->Lan->PizzaWave->find('all', array(
			'conditions' => $conditions,
			'order' => array(
				'PizzaWave.time_start ASC'
			)
				)
		);

		return $current_wave;
	}

	public function isOrderable($id){
		$this->id = $id;

		if(!$this->exists()){
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->read(array('time_end', 'lan_id'));

		if($this->Lan->isPublished($this->data['PizzaWave']['lan_id'])){
			if($this->data['PizzaWave']['time_end'] > date('Y-m-d H:i:s')){
				return true;
			}
		}

		return false;
	}

}