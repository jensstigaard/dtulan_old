<?php

class PizzaWave extends AppModel {

    public $hasMany = array('PizzaOrder' => array('foreignKey' => 'pizza_wave_id'));
    public $belongsTo = array('LanPizzaMenu');
    public $order = array(
        'PizzaWave.time_start' => 'desc'
    );
    public $validate = array(
        'time_start' => array(
            'bigger than end' => array(
                'rule' => 'validateDates',
                'message' => 'Invalid start-/end-time',
            ),
            'validInterval' => array(
                'rule' => 'validInterval',
                'message' => 'There already exist a pizza wave in this interval'
            )
        )
    );

    public function validateDates($check) {
        if ($check['time_start'] >= $this->data['PizzaWave']['time_end']) {
            $this->invalidate('time_end', 'Invalid start-/end-time');
            return false;
        }
        return true;
    }

    public function validInterval($check) {
        return $this->find('count', array(
                    'conditions' => array(
                        'OR' => array(
                            array(
                                'PizzaWave.time_start >= ' => $this->data['PizzaWave']['time_start'],
                                'PizzaWave.time_start < ' => $this->data['PizzaWave']['time_end'],
                            ),
                            array(
                                'PizzaWave.time_end > ' => $this->data['PizzaWave']['time_start'],
                                'PizzaWave.time_end <= ' => $this->data['PizzaWave']['time_end'],
                            ),
                            array(
                                'PizzaWave.time_start <= ' => $this->data['PizzaWave']['time_start'],
                                'PizzaWave.time_end >= ' => $this->data['PizzaWave']['time_end'],
                            )
                        )
                    )
                        )
                ) == 0;
    }

    public function isOnAir() {
        $current_time = date('Y-m-d H:i:s');

        $current_wave = $this->Lan->PizzaWave->find('count', array(
            'conditions' => array(
                'PizzaWave.time_end >' => $current_time,
                'PizzaWave.time_start <' => $current_time,
                'PizzaWave.status' => 1
            )
                )
        );

        return $current_wave;
    }

    public function isOrderable() {

        if (!$this->exists()) {
            throw new NotFoundException(__('Pizza wave not found with ID: "' . $this->id . '" in function isOrderable'));
        }

        $this->read(array('time_end', 'lan_id', 'status'));

		$this->Lan->id = $this->data['PizzaWave']['lan_id'];

        if ($this->Lan->isPublished()) {
            if ($this->data['PizzaWave']['status'] == 1 && $this->data['PizzaWave']['time_end'] > date('Y-m-d H:i:s')) {
                return true;
            }
        }

        return false;
    }

    public function getItemList($id) {
        $this->id = $id;

        if (!$this->exists()) {
            throw new NotFoundException(__('Pizza wave not found with ID: ' . $id));
        }

        $this->PizzaOrder->unbindModel(array('belongsTo' => array('User', 'PizzaWave')));
        $this->PizzaOrder->PizzaOrderItem->unbindModel(array('belongsTo' => array('PizzaOrder')));
        $this->PizzaOrder->PizzaOrderItem->PizzaPrice->unbindModel(array('hasMany' => array('PizzaOrderItem')));
        $pizza_orders = $this->PizzaOrder->find('all', array(
            'conditions' => array(
                'PizzaOrder.pizza_wave_id' => $id
            ),
            'recursive' => 3
                )
        );

        $pizza_wave_items = array();

        foreach ($pizza_orders as $pizza_order) {
            foreach ($pizza_order['PizzaOrderItem'] as $pizza_order_item) {

                if (!isset($pizza_wave_items[$pizza_order_item['PizzaPrice']['id']])) {
                    $pizza_wave_items[$pizza_order_item['PizzaPrice']['id']] = array(
                        'quantity' => 0,
                        'pizza_title' => $pizza_order_item['PizzaPrice']['Pizza']['title'],
                        'pizza_number' => $pizza_order_item['PizzaPrice']['Pizza']['number'],
                        'pizza_type' => $pizza_order_item['PizzaPrice']['PizzaType']['title']
                    );
                }
                $pizza_wave_items[$pizza_order_item['PizzaPrice']['id']]['quantity'] += $pizza_order_item['quantity'];
            }
        }

        function compare($a, $b) {
            if (strcmp($a['pizza_number'], $b['pizza_number']) == 0) {
                if (strcmp($a['pizza_title'], $b['pizza_title']) == 0) {
                    if (strcmp($a['pizza_type'], $b['pizza_type']) == 0) {
                        return 0;
                    }
                    return strcmp($a['pizza_type'], $b['pizza_type']);
                }
                return strcmp($a['pizza_title'], $b['pizza_title']);
            }
            return intval($a['pizza_number']) > intval($b['pizza_number']);
        }

        // Sort the items
        usort($pizza_wave_items, "compare");

        return $pizza_wave_items;
    }

    public function getOrderList($id) {
        $this->id = $id;

        if (!$this->exists()) {
            throw new NotFoundException(__('Pizza wave not found with ID: ' . $id));
        }

        $this->PizzaOrder->unbindModel(array('belongsTo' => array('PizzaWave')));
        $this->PizzaOrder->PizzaOrderItem->unbindModel(array('belongsTo' => array('PizzaOrder')));
        $this->PizzaOrder->PizzaOrderItem->PizzaPrice->unbindModel(array('hasMany' => array('PizzaOrderItem')));

        $this->PizzaOrder->User->unbindModel(
                array('hasMany' => array(
                        'Crew',
                        'LanSignup',
                        'LanInvite',
                        'LanInviteSent',
                        'Payment',
                        'PizzaOrder',
                        'TeamUser'
                    ),
                    'hasOne' => array(
                        'Admin',
                        'UserPasswordTicket',
                        'QrCode'
                    )
                )
        );

        $pizza_orders = $this->PizzaOrder->find('all', array(
            'conditions' => array(
                'PizzaOrder.pizza_wave_id' => $id
            ),
            'recursive' => 3,
            'order' => array(
                'status' => 'asc',
                'time' => 'asc'
            )
                )
        );

        return $pizza_orders;
    }

    public function getOrdersSum($id) {

        $this->id = $id;

        if (!$this->exists()) {
            throw new NotFoundException(__('Pizza wave not found with ID: ' . $id));
        }

        $pizza_orders = $this->PizzaOrder->find('all', array(
            'conditions' => array(
                'PizzaOrder.pizza_wave_id' => $id
            ),
            'fields' => array(
                'PizzaOrder.id'
            )
                )
        );

        $pizza_order_ids = array();

        foreach ($pizza_orders as $pizza_order) {
            $pizza_order_ids[] = $pizza_order['PizzaOrder']['id'];
        }

        $sum = $this->PizzaOrder->PizzaOrderItem->find('all', array(
            'fields' => array(
                'sum(PizzaOrderItem.quantity * PizzaOrderItem.price) AS ctotal'
            ),
            'conditions' => array(
                'PizzaOrderItem.pizza_order_id' => $pizza_order_ids
            )
                )
        );

        return $sum[0][0]['ctotal'];
    }

    public function getStatus($id) {
        $this->id = $id;

        if (!$this->exists()) {
            throw new NotFoundException('Pizza wave not found');
        }

        $this->read(array('status'));

        return $this->data['PizzaWave']['status'];
    }

    public function isDelivered($id) {
        return $this->getStatus($id) == 3;
    }

    public function isCompleted($id) {
        return $this->getStatus($id) == 4;
    }

    public function getTotalPizzaOrdersByLan($id = '') {
        $db = $this->getDataSource();
        $total = $db->fetchAll("select count(PizzaOrder.id) as PizzaOrders from pizza_waves as PizzaWave inner join pizza_orders as PizzaOrder on PizzaOrder.pizza_wave_id = PizzaWave.id where PizzaWave.lan_id = ?", array($id));
        return $total[0][0]['PizzaOrders'];
    }

    public function getTotalPizzasByLan($id = '') {
        $db = $this->getDataSource();
        $total = $db->fetchAll("select sum(PizzaOrderItem.price * PizzaOrderItem.quantity) as Total from pizza_waves as PizzaWave inner join pizza_orders as PizzaOrder on PizzaOrder.pizza_wave_id = PizzaWave.id inner join pizza_order_items as PizzaOrderItem on PizzaOrderItem.pizza_order_id = PizzaOrder.id where PizzaWave.lan_id = ?", array($id));
        return $total[0][0]['Total'];
    }

}
