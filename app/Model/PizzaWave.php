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

		$current_wave = $this->find('all', array(
			'conditions' => $conditions,
			'order' => array(
				'PizzaWave.time_start ASC'
			)
				)
		);

		return $current_wave;
	}

	public function isOrderable($id) {
		$this->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->read(array('time_end', 'lan_id'));

		if ($this->Lan->isPublished($this->data['PizzaWave']['lan_id'])) {
			if ($this->data['PizzaWave']['time_end'] > date('Y-m-d H:i:s')) {
				return true;
			}
		}

		return false;
	}

	public function getItemList($id) {
		$this->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->PizzaOrder->unbindModel(array('belongsTo' => array('User', 'PizzaWave')));
		$this->PizzaOrder->PizzaOrderItem->unbindModel(array('belongsTo' => array('PizzaOrder')));
		$this->PizzaOrder->PizzaOrderItem->PizzaPrice->unbindModel(array('hasMany' => array('PizzaOrderItem')));
		$pizza_orders = $this->PizzaOrder->find('all', array(
			'conditions' => array(
				'PizzaOrder.pizza_wave_id' => $id
			),
			'recursive' => 3
				)
		);

		$pizza_wave_items = array();

		foreach ($pizza_orders as $pizza_order) {
			foreach ($pizza_order['PizzaOrderItem'] as $pizza_order_item) {

				if (!isset($pizza_wave_items[$pizza_order_item['id']])) {
					$pizza_wave_items[$pizza_order_item['id']] = array(
						'quantity' => 0,
						'pizza_title' => $pizza_order_item['PizzaPrice']['Pizza']['title'],
						'pizza_number' => $pizza_order_item['PizzaPrice']['Pizza']['number'],
						'pizza_type' => $pizza_order_item['PizzaPrice']['PizzaType']['title']
					);
				}
				$pizza_wave_items[$pizza_order_item['id']]['quantity'] += $pizza_order_item['quantity'];
			}
		}

		function compare($a, $b) {
			if (strcmp($a['pizza_number'], $b['pizza_number']) == 0) {
				if (strcmp($a['pizza_title'], $b['pizza_title']) == 0) {
					if (strcmp($a['pizza_type'], $b['pizza_type']) == 0) {
						return 0;
					}
					return strcmp($a['pizza_type'], $b['pizza_type']);
				}
				return strcmp($a['pizza_title'], $b['pizza_title']);
			}
			return strcmp($a['pizza_number'], $b['pizza_number']);
		}

		// Sort the items
		usort($pizza_wave_items, "compare");

		return $pizza_wave_items;
	}

	public function getItemListPrintable($pizza_wave_items) {

		$content = '';

		foreach ($pizza_wave_items as $item) {
			$content.='<tr>';
			$content.='<td>' . $item['quantity'] . '</td>';
			$content.='<td>' . $item['pizza_number'] . '</td>';
			$content.='<td>' . $item['pizza_title'] . '</td>';
			$content.='<td>' . $item['pizza_type'] . '</td>';
			$content.='</tr>';
		}

		return $content;
	}

}