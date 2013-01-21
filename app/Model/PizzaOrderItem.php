<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaOrderItem
 *
 * @author Jens
 */
class PizzaOrderItem extends AppModel {

	public $belongsTo = array('PizzaOrder', 'PizzaPrice');
	public $validate = array(
		 'pizza_price_id' => array(
			  'exists' => array(
					'rule' => 'validatePizzaPriceIdExists',
					'message' => 'Pizza Price does not exist'
			  ),
			  'validate Pizza' => array(
					'rule' => 'validatePizzaPriceIdConnectedWave',
					'message' => 'Invalid pizza price in wave'
			  )
		 ),
	);

	public function validatePizzaPriceIdExists($check) {
		$this->PizzaPrice->id = $check['pizza_price_id'];

		return $this->PizzaPrice->exists();
	}

	public function validatePizzaPriceIdConnectedWave($check) {
		$db = $this->getDataSource();

		$getByLanPizzaMenu = $db->fetchAll("
			SELECT LanPizzaMenu.pizza_menu_id AS PizzaMenuId
				FROM `lan_pizza_menus` AS LanPizzaMenu
			INNER JOIN `pizza_waves` AS PizzaWave
				ON LanPizzaMenu.id = PizzaWave.lan_pizza_menu_id
			WHERE PizzaWave.id = ?
			", array($this->PizzaOrder->data['PizzaOrder']['pizza_wave_id']));

		$getByPizzaPrice = $db->fetchAll("
			SELECT PizzaCategory.pizza_menu_id AS PizzaMenuId
				FROM `pizza_categories` AS PizzaCategory
			INNER JOIN `pizzas` AS Pizza
				ON PizzaCategory.id = Pizza.pizza_category_id
			INNER JOIN `pizza_prices` AS PizzaPrice
				ON Pizza.id = PizzaPrice.pizza_id 
			WHERE PizzaPrice.id = ?
			", array($check['pizza_price_id']));

		return $getByLanPizzaMenu[0]['LanPizzaMenu']['PizzaMenuId'] == $getByPizzaPrice[0]['PizzaCategory']['PizzaMenuId'];
	}

}

?>
