<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanPizzaMenu
 *
 * @author Jens
 */
class LanPizzaMenu extends AppModel {

	public $belongsTo = array(
		 'Lan',
		 'PizzaMenu'
	);
	public $hasMany = array(
		 'PizzaWave'
	);

	public function isUserConnected() {
		$this->read(array('lan_id'));

		$this->Lan->id = $this->data['LanPizzaMenu']['lan_id'];

		return $this->Lan->isUserAttending();
	}

	public function getPizzaWavesAvailable() {

		$current_wave = $this->PizzaWave->find('all', array(
			 'conditions' => array(
				  'PizzaWave.status' => 1,
				  'PizzaWave.time_close >' => date('Y-m-d H:i:s'),
				  'PizzaWave.lan_pizza_menu_id' => $this->id
			 ),
			 'order' => array(
				  'PizzaWave.time_close ASC'
			 )
				  )
		);

		return $current_wave;
	}

	public function getPizzaWaveSelected() {

		if ($this->PizzaWave->id && $this->PizzaWave->exists()) {
			$cond = array(
				 'PizzaWave.lan_pizza_menu_id' => $this->id,
				 'PizzaWave.status' => 1,
				 'PizzaWave.id' => $this->PizzaWave->id,
			);
		} else {
			$cond = array(
				 'PizzaWave.lan_pizza_menu_id' => $this->id,
				 'PizzaWave.status' => 1,
				 'PizzaWave.time_close >' => date('Y-m-d H:i:s'),
			);
		}

		return $this->PizzaWave->find('first', array(
						'conditions' => $cond,
							 )
		);
	}

}

?>
