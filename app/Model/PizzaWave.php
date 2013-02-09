<?php

App::uses('CakeEvent', 'Event');

class PizzaWave extends AppModel {

	public $useTable = 'pizza_waves';
	public $hasMany = array(
		 'PizzaOrder' => array(
			  'foreignKey' => 'pizza_wave_id'
		 )
	);
	public $belongsTo = array(
		 'LanPizzaMenu'
	);
	public $order = array(
		 'PizzaWave.time_close' => 'desc'
	);
	public $validate = array(
		 'time_close' => array(
			  'validTime' => array(
					'rule' => 'validTime',
					'message' => 'There already exist a pizza wave in this time'
			  )
		 ),
	);

	// Remember to refactor!!!
	public function validateTime($check) {
		// $check['time_close'];

		return true;
	}

	public function isOnAir() {
		$current_time = date('Y-m-d H:i:s');

		$current_wave = $this->Lan->PizzaWave->find('count', array(
			 'conditions' => array(
				  'PizzaWave.time_close >' => $current_time,
				  'PizzaWave.status' => 1
			 )
				  )
		);

		return $current_wave;
	}

	public function isOrderable() {

		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza wave not found with ID: "' . $this->id . '" in function isOrderable'));
		}

		$this->read(array('status', 'time_close', 'lan_pizza_menu_id'));

		$this->LanPizzaMenu->id = $this->data['PizzaWave']['lan_pizza_menu_id'];

		$this->LanPizzaMenu->read(array('lan_id'));

		$this->LanPizzaMenu->Lan->id = $this->LanPizzaMenu->data['LanPizzaMenu']['lan_id'];

		$this->LanPizzaMenu->Lan->LanSignup->User->id = $this->getLoggedInId();

		if ($this->LanPizzaMenu->Lan->isUserAttending() && $this->data['PizzaWave']['status'] == 1 && $this->data['PizzaWave']['time_close'] > date('Y-m-d H:i:s')) {
			return true;
		}

		return false;
	}

	public function isUserConnected() {
		$this->read(array('lan_pizza_menu_id'));

		$this->LanPizzaMenu->id = $this->data['PizzaWave']['lan_pizza_menu_id'];

		return $this->LanPizzaMenu->isUserConnected();
	}

	public function getItemList() {

		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza wave not found with ID: ' . $this->id));
		}

		$pizza_orders = $this->PizzaOrder->find('all', array(
			 'conditions' => array(
				  'PizzaOrder.pizza_wave_id' => $this->id
			 ),
			 'contain' => array(
				  'PizzaOrderItem' => array(
						'PizzaPrice' => array(
							 'Pizza',
							 'PizzaType'
						)
				  )
			 )
				  ));

		$pizza_wave_items = array();

		foreach ($pizza_orders as $pizza_order) {
			foreach ($pizza_order['PizzaOrderItem'] as $pizza_order_item) {

				if (!isset($pizza_wave_items[$pizza_order_item['PizzaPrice']['id']])) {
					$pizza_wave_items[$pizza_order_item['PizzaPrice']['id']] = array(
						 'quantity' => 0,
						 'pizza_title' => $pizza_order_item['PizzaPrice']['Pizza']['title'],
						 'pizza_number' => $pizza_order_item['PizzaPrice']['Pizza']['number'],
						 'pizza_type' => $pizza_order_item['PizzaPrice']['PizzaType']['title']
					);
				}
				$pizza_wave_items[$pizza_order_item['PizzaPrice']['id']]['quantity'] += $pizza_order_item['quantity'];
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
			return intval($a['pizza_number']) > intval($b['pizza_number']);
		}

		// Sort the items
		usort($pizza_wave_items, "compare");

		return $pizza_wave_items;
	}

	public function getOrderList($id) {
		$this->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza wave not found with ID: ' . $id));
		}

		$pizza_orders = $this->PizzaOrder->find('all', array(
			 'conditions' => array(
				  'PizzaOrder.pizza_wave_id' => $id
			 ),
			 'order' => array(
				  'status' => 'asc',
				  'time' => 'asc'
			 ),
			 'contain' => array(
				  'User',
				  'PizzaOrderItem' => array(
						'PizzaPrice' => array(
							 'Pizza',
							 'PizzaType'
						)
				  )
			 )
				  )
		);

		return $pizza_orders;
	}

	public function getOrdersSum() {

		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza wave not found with ID: ' . $id));
		}

		$pizza_orders = $this->PizzaOrder->find('all', array(
			 'conditions' => array(
				  'PizzaOrder.pizza_wave_id' => $this->id
			 ),
			 'fields' => array(
				  'PizzaOrder.id'
			 )
				  )
		);

		$pizza_order_ids = array();

		foreach ($pizza_orders as $pizza_order) {
			$pizza_order_ids[] = $pizza_order['PizzaOrder']['id'];
		}

		$sum = $this->PizzaOrder->PizzaOrderItem->find('all', array(
			 'fields' => array(
				  'sum(PizzaOrderItem.quantity * PizzaOrderItem.price) AS ctotal'
			 ),
			 'conditions' => array(
				  'PizzaOrderItem.pizza_order_id' => $pizza_order_ids
			 )
				  )
		);

		return $sum[0][0]['ctotal'];
	}

	public function getStatus($id) {
		$this->id = $id;

		if (!$this->exists()) {
			throw new NotFoundException('Pizza wave not found');
		}

		$this->read(array('status'));

		return $this->data['PizzaWave']['status'];
	}

	public function isDelivered($id) {
		return $this->getStatus($id) == 3;
	}

	public function isCompleted($id) {
		return $this->getStatus($id) == 4;
	}

	public function sendEmail() {

		if (!$this->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$pizza_wave = $this->find('first', array(
			 'conditions' => array(
				  'PizzaWave.id' => $this->id
			 ),
			 'fields' => array(
				  'status'
			 ),
			 'contain' => array(
				  'LanPizzaMenu' => array(
						'fields' => array(
							 'id'
						)
				  )
			 )
				  ));

		$this->LanPizzaMenu->id = $pizza_wave['LanPizzaMenu']['id'];

		if (!$this->LanPizzaMenu->exists()) {
			throw new NotFoundException(__('LanPizzaMenu not found with ID: ' . $this->LanPizzaMenu->id));
		}

		$lan_pizza_menu = $this->LanPizzaMenu->read('pizza_menu_id');

		$this->LanPizzaMenu->PizzaMenu->id = $lan_pizza_menu['LanPizzaMenu'];
		if (!$this->LanPizzaMenu->PizzaMenu->exists()) {
			throw new NotFoundException(__('PizzaMenu not found with ID: ' . $this->LanPizzaMenu->PizzaMenu->id));
		}

		$pizza_menu = $this->LanPizzaMenu->PizzaMenu->read(array('email'));

		if ($pizza_wave['PizzaWave']['status'] < 1) {
			throw new MethodNotAllowedException(__('Wave not open yet'));
		}
		if ($pizza_wave['PizzaWave']['status'] > 1) {
			throw new MethodNotAllowedException(__('Email for pizza wave already sent'));
		}

		$pizza_wave_items = $this->getItemList();

		if (!count($pizza_wave_items)) {
			throw new NotFoundException(__('No items found in pizza wave'));
		}

		$this->set(array('status' => 2));

		$event = new CakeEvent('Model.PizzaWave.sendPizzaWaveEmail', $this, array(
						'email_to' => $pizza_menu['PizzaMenu']['email'],
						'pizza_wave_items' => $pizza_wave_items
				  ));
		$this->getEventManager()->dispatch($event);


		$dataSource = $this->getDataSource();
		$dataSource->begin();

		if ($this->save() && $event->result['success']) {
			$dataSource->commit();
			return true;
		}

		$dataSource->rollback();
		return false;
	}

}
