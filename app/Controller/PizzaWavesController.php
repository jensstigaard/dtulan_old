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

		$this->set('pizza_wave_items', $this->PizzaWave->getItemList($id));
	}

	public function send_email($id) {

		App::uses('CakeEmail', 'Network/Email');
		
		$this->PizzaWave->id = $id;

		if (!$this->PizzaWave->exists()) {
			throw new NotFoundException(__('Pizza wave not found'));
		}

		$this->PizzaWave->read(array('status'));

		if ($this->PizzaWave->data['PizzaWave']['status'] > 0) {
			throw new MethodNotAllowedException(__('Email for pizza wave already sent'));
		}

		$pizza_wave_items = $this->PizzaWave->getItemList($id);

		if(!count($pizza_wave_items)){
			throw new NotFoundException(__('No items found in pizza wave'));
		}

		$email = new CakeEmail();
		$email->config('smtp');
		$email->emailFormat('html');
		$email->template('pizza_wave_items_to_pizzaria');
		$email->from(array('no-reply@dtu-lan.dk' => 'DTU LAN site - No reply'));
		$email->to('jens@stigaard.info');
		$email->subject('DTU LAN Party - Ny pizza liste');
		$email->send($pizza_wave_items);
	}

}