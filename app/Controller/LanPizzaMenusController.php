<?php

class LanPizzaMenusController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->LanPizzaMenu->isYouAdmin()) {
			return true;
		}
		return false;
	}

	public function view($id, $pizza_wave_id = null) {

		$this->set('title_for_layout', 'Pizzas');

		$this->LanPizzaMenu->id = $id;

		if (!$this->LanPizzaMenu->exists()) {
			throw new NotFoundException('Lan pizza menu not found with ID #' . $id);
		}

		$this->LanPizzaMenu->read(array('lan_id', 'pizza_menu_id'));

		$this->LanPizzaMenu->Lan->id = $this->LanPizzaMenu->data['LanPizzaMenu']['lan_id'];
		$this->LanPizzaMenu->PizzaMenu->id = $this->LanPizzaMenu->data['LanPizzaMenu']['pizza_menu_id'];

		if (!$this->LanPizzaMenu->Lan->exists()) {
			throw new NotFoundException('LAN not found with ID #' . $this->LanPizzaMenu->Lan->id);
		}

		if (!$this->LanPizzaMenu->PizzaMenu->exists()) {
			throw new NotFoundException('Pizza menu not found with ID #' . $this->LanPizzaMenu->PizzaMenu->id);
		}

		if ($pizza_wave_id != null) {
			$this->LanPizzaMenu->PizzaWave->id = $pizza_wave_id;
		}

		$pizza_wave = $this->LanPizzaMenu->getPizzaWaveSelected();

		$is_orderable = false;
		if ($pizza_wave) {
			$is_orderable = true;
		}

		$this->set(compact('pizza_wave', 'is_orderable'));
		$this->set('pizza_waves', $this->LanPizzaMenu->getPizzaWavesAvailable());
		$this->set('pizza_menu', $this->LanPizzaMenu->PizzaMenu->read());
		$this->set('pizza_categories', $this->LanPizzaMenu->PizzaMenu->getList());
	}

	public function add($lan_id) {
		$this->LanPizzaMenu->Lan->id = $lan_id;

		if (!$this->LanPizzaMenu->Lan->exists()) {
			throw new NotFoundException('Lan not found with ID #' . $lan_id);
		}

		if ($this->request->is('post')) {
			$this->request->data['LanPizzaMenu']['lan_id'] = $lan_id;

			if ($this->LanPizzaMenu->save($this->request->data)) {
				$this->Session->setFlash('Your pizza menu has been connected', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to connect pizza menu', 'default', array(), 'bad');
			}
		}

		$this->LanPizzaMenu->Lan->read(array('title'));

		$this->set('lan_title', $this->LanPizzaMenu->Lan->data['Lan']['title']);

		$this->set('pizzaMenus', $this->LanPizzaMenu->PizzaMenu->find('list', array(
					'conditions' => array(
						'NOT' => array(
							'PizzaMenu.id' => $this->LanPizzaMenu->Lan->getPizzaMenuIds()
						)
					)
				)));
	}

}

?>
