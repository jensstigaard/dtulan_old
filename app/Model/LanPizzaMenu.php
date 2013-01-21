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
		 'Lan', 'PizzaMenu'
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
		$current_time = date('Y-m-d H:i:s');

		$current_wave = $this->PizzaWave->find('all', array(
			 'conditions' => array(
				  'PizzaWave.status' => 1,
				  'PizzaWave.time_end >' => $current_time,
				  'PizzaWave.lan_pizza_menu_id' => $this->id
			 ),
			 'order' => array(
				  'PizzaWave.time_start ASC'
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
			$current_time = date('Y-m-d H:i:s');

			$cond = array(
				 'PizzaWave.lan_pizza_menu_id' => $this->id,
				 'PizzaWave.status' => 1,
				 'PizzaWave.time_end >' => $current_time,
				 'PizzaWave.time_start <' => $current_time,
			);
		}

		return $this->PizzaWave->find('first', array(
						'conditions' => $cond,
							 )
		);
	}

}

?>
