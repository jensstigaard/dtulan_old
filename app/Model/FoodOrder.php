<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SweetOrder
 *
 * @author Jens
 */
class FoodOrder extends AppModel {
	public $belongsTo = array(
		'User', 'LanFoodMenu'
	);

	public $hasMany = array(
		'FoodOrderItem'
	);

	public $order = array(
		'status' => 'asc',
		'time' => 'desc'
	);

	public $validate = array(
		'lan_food_menu_id' => array(
			'isValid' => array(
				'rule' => 'validLanFoodMenu',
				'message' => 'Invalid food menu'
			)
		)
	);

	public function validLanFoodMenu($check){
		$this->LanFoodMenu->id = $check['lan_food_menu_id'];

		if(!$this->LanFoodMenu->exists()){
			throw new NotFoundException('Lan Food Menu not found with ID #'.$this->LanFoodMenu->id);
		}

		return $this->LanFoodMenu->isOrderable();
	}
	
	
	public function getItemsSum(){	
		$sum = $this->FoodOrderItem->find('all', array(
			'fields' => array(
				'sum(FoodOrderItem.quantity * FoodOrderItem.price) AS ctotal'
			),
			'conditions' => array(
				'FoodOrderItem.food_order_id' => $this->id
			)
				)
		);
		
		return $sum[0][0]['ctotal'];
	}
}

?>
