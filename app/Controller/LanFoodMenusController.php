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

		$this->set('lan_food_menu_id', $id);
		$this->set('food_menu', $this->LanFoodMenu->FoodMenu->read());
		$this->set('lan', $this->LanFoodMenu->Lan->read());

		$this->set('categories', $this->LanFoodMenu->getCategories());

		$this->set('is_orderable_food', $this->LanFoodMenu->data['LanFoodMenu']['is_orderable']);

	}

	public function add($lan_id) {
		$this->LanFoodMenu->Lan->id = $lan_id;

		if (!$this->LanFoodMenu->Lan->exists()) {
			throw new NotFoundException('Lan not found with ID #' . $lan_id);
		}

		if ($this->request->is('post')) {
			$this->request->data['LanFoodMenu']['lan_id'] = $lan_id;

			if ($this->LanFoodMenu->save($this->request->data)) {
				$this->Session->setFlash('Your food menu has been connected', 'default', array('class' => 'message success'), 'good');
			} else {
				$this->Session->setFlash('Unable to connect pizza menu', 'default', array(), 'bad');
			}
		}

		$this->LanFoodMenu->Lan->read(array('title'));

		$this->set('lan_title', $this->LanFoodMenu->Lan->data['Lan']['title']);

		$this->set('foodMenus', $this->LanFoodMenu->FoodMenu->find('list', array(
					'conditions' => array(
						'NOT' => array(
							'FoodMenu.id' => $this->LanFoodMenu->Lan->getFoodMenuIds()
						)
					)
				)));
	}

}

?>
