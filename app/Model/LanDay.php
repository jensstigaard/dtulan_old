<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanDay
 *
 * @author Jens
 */
class LanDay extends AppModel {

    public $belongsTo = array('Lan');
    public $hasMany = array('LanSignupDay');
    public $order = array('LanDay.date' => 'asc');

    public function seatsLeft($id) {

        $this->id = $id;

        if (!$this->exists()) {
            throw new NotFoundException('Lan day not found');
        }

        $this->recursive = 1;
        $lan_day = $this->read();

        return $lan_day['Lan']['max_participants'] - count($lan_day['LanSignupDay']);
    }

    public function getLanDaysFromLan($id = '') {
        return $this->find('all', array(
                    'conditions' => array(
                        'LanDay.lan_id' => $id
                    ),
                    'order' => array(
                        'LanDay.date ASC',
                    )
                        )
        );
    }

}

?>
