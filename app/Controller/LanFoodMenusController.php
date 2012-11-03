<?php

class LanFoodMenusController extends AppController {

	public function view($id) {
		$this->set('title_for_layout', "Sweets n' soda");

		$this->LanFoodMenu->id = $id;

		if (!$this->LanFoodMenu->exists()) {
			throw new NotFoundException('Lan Food Menu not found with ID #' . $id);
		}

		$this->LanFoodMenu->read(array('food_menu_id', 'lan_id', 'is_orderable'));

		$this->LanFoodMenu->FoodMenu->id = $this->LanFoodMenu->data['LanFoodMenu']['food_menu_id'];
		$this->LanFoodMenu->Lan->id = $this->LanFoodMenu->data['LanFoodMenu']['lan_id'];

		$this->set('food_menu', $this->LanFoodMenu->FoodMenu->read());
		$this->set('lan', $this->LanFoodMenu->Lan->read());

		$this->set('categories', $this->LanFoodMenu->getCategories());

		$this->set('is_orderable_food', $this->LanFoodMenu->data['LanFoodMenu']['is_orderable']);
	}

}

?>
