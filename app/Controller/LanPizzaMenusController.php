<?php

class LanPizzaMenusController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->PizzaMenu->isYouAdmin()) {
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

}

?>
