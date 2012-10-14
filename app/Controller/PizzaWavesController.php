<?php

class PizzaWavesController extends AppController {

	public $helpers = array('Html', 'Form');
	public $uses = 'PizzaWave';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)) {
			return true;
		}
		return false;
	}

	public function index($lan_id = null) {

		$this->PizzaWave->Lan->id = $lan_id;

		$cond = array();
		if ($this->PizzaWave->Lan->exists()) {
			$cond['PizzaWave.lan_id'] = $lan_id;
		}

		$pizza_waves = $this->PizzaWave->find('all', array(
			'conditions' => $cond
				)
		);

		$this->set(compact('pizza_waves'));
	}

	public function add($lan_id = null) {
		if ($lan_id == null) {
			throw new NotFoundException('Invalid LAN id');
		}

		$this->PizzaWave->Lan->id = $lan_id;

		if (!$this->PizzaWave->Lan->exists()) {
			throw new NotFoundException('LAN not found with id ' . $lan_id);
		}

		if ($this->request->is('post')) {

			$this->request->data['PizzaWave']['lan_id'] = $lan_id;

			if ($this->PizzaWave->save($this->request->data)) {
				$this->Session->setFlash('Your PizzaWave has been added.', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add this PizzaWave.', 'default', array(), 'bad');
			}
		}

		$this->set('lan', $this->PizzaWave->Lan->read());
	}

	public function view($id) {
		$this->PizzaWave->id = $id;

		if (!$this->PizzaWave->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->PizzaWave->recursive = 0;
		$this->set('pizza_wave', $this->PizzaWave->read());

		if($this->PizzaWave->data['PizzaWave']['status']==3){
			$this->set('pizza_wave_orders', $this->PizzaWave->getOrderList($id));
		}
		else{
			$this->set('pizza_wave_items', $this->PizzaWave->getItemList($id));
		}

	}

	public function send_email($id) {

		App::uses('CakeEmail', 'Network/Email');

		$this->PizzaWave->id = (int) $id;

		if (!$this->PizzaWave->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->PizzaWave->read(array('status'));

		if ($this->PizzaWave->data['PizzaWave']['status'] < 1) {
			throw new MethodNotAllowedException(__('Wave not open yet'));
		}
		if ($this->PizzaWave->data['PizzaWave']['status'] > 1) {
			throw new MethodNotAllowedException(__('Email for pizza wave already sent'));
		}

		$pizza_wave_items = $this->PizzaWave->getItemList($id);

		if (!count($pizza_wave_items)) {
			throw new NotFoundException(__('No items found in pizza wave'));
		}

//		debug($content_for_email);

		$email = new CakeEmail();
		$email->config('smtp');
		$email->emailFormat('html');
		$email->template('pizza_wave_to_pizzaria');
		$email->from(array('no-reply@dtu-lan.dk' => 'DTU LAN site - No reply'));
		$email->to('jens@stigaard.info', 'pizza@dtu-lan.dk'); //
		$email->viewVars(array('pizza_wave_items' => $pizza_wave_items, 'title_for_layout' => 'Pizza bestilling'));
		$email->subject('DTU LAN Party - Ny pizza liste');

		$this->PizzaWave->set(array('status' => 2));

//		debug($this->PizzaWave->data);

		if (
				$this->PizzaWave->save()
				&&
				$email->send()
		) {
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
