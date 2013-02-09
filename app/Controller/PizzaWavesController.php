<?php

class PizzaWavesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->PizzaWave->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function add($lan_pizza_menu_id) {

		$this->PizzaWave->LanPizzaMenu->id = $lan_pizza_menu_id;

		if (!$this->PizzaWave->LanPizzaMenu->exists()) {
			throw new NotFoundException('Lan pizza menu not found with id ' . $lan_pizza_menu_id);
		}

		if ($this->request->is('post')) {

			$this->request->data['PizzaWave']['lan_pizza_menu_id'] = $lan_pizza_menu_id;

			if ($this->PizzaWave->save($this->request->data)) {
				$this->Session->setFlash('Pizza-wave has been added!', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to add this pizza-wave', 'default', array(), 'bad');
			}
			$this->redirect($this->referer());
		}

		$this->set('lan_pizza_menu', $this->PizzaWave->LanPizzaMenu->read(array('id', 'PizzaMenu.title', 'Lan.title')));
	}

	public function view($id) {
		$this->PizzaWave->id = $id;

		if (!$this->PizzaWave->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->PizzaWave->recursive = 0;
		$this->set('pizza_wave', $this->PizzaWave->read());

		if ($this->PizzaWave->data['PizzaWave']['status'] == 3) {
			$pizza_wave_orders = $this->PizzaWave->getOrderList($id);
			$this->PizzaWave->dateToNiceArray($pizza_wave_orders, 'PizzaOrder');
			$this->set(compact('pizza_wave_orders'));
		} else {
			$this->set('pizza_wave_items', $this->PizzaWave->getItemList($id));
		}
	}

	public function send_email($id) {

		$this->PizzaWave->id = $id;

		if ($this->PizzaWave->sendEmail()) {
			$this->Session->setFlash('Your email has been send', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Unable to send email for this PizzaWave.', 'default', array(), 'bad');
		}

		$this->redirect(array('action' => 'view', $id));
	}

	public function mark_open($id) {

		$this->PizzaWave->id = $id;

		if (!$this->PizzaWave->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->PizzaWave->read(array('status'));

		if ($this->PizzaWave->data['PizzaWave']['status'] > 0) {
			throw new MethodNotAllowedException(__('Pizza wave already marked as open'));
		}

		$this->PizzaWave->set(array('status' => 1));

		if ($this->PizzaWave->save()) {
			$this->Session->setFlash('Pizza wave marked as open', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Unable to mark pizza wave as open', 'default', array(), 'bad');
		}

		$this->redirect(array('action' => 'view', $id));
	}

	public function mark_received($id) {

		$this->PizzaWave->id = $id;

		if (!$this->PizzaWave->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->PizzaWave->read(array('status'));

		if ($this->PizzaWave->data['PizzaWave']['status'] < 2) {
			throw new MethodNotAllowedException(__('Information not sent to pizzaria yet'));
		} elseif ($this->PizzaWave->data['PizzaWave']['status'] > 2) {
			throw new MethodNotAllowedException(__('Pizza wave already marked as received'));
		}

		$this->PizzaWave->set(array('status' => 3));

		if ($this->PizzaWave->save()) {
			$this->Session->setFlash('Pizza wave marked as received', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('Unable to mark pizza wave as received', 'default', array(), 'bad');
		}
		$this->redirect(array('action' => 'view', $id));
	}

}
