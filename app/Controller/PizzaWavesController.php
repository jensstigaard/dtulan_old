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

	public function index() {
		$this->set('pizzaWaves', $this->PizzaWave->find('all'));
	}

	public function add($lan_id = null) {
		if($lan_id == null){
			throw new NotFoundException('Invalid LAN id');
		}

		$this->PizzaWave->Lan->id = $lan_id;

		if(!$this->PizzaWave->Lan->exists()){
			throw new NotFoundException('LAN not found with id '. $lan_id);
		}

		if ($this->request->is('post')) {

			$this->request->data['PizzaWave']['lan_id'] = $lan_id;

			if ($this->PizzaWave->save($this->request->data)) {
				$this->Session->setFlash('Your PizzaWave has been added.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add this PizzaWave.');
			}
		}

		$this->set('lan', $this->PizzaWave->Lan->read());
	}
}
?>
