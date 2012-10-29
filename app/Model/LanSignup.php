<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanSignup
 *
 * @author Jens
 */
class LanSignup extends AppModel {

    public $belongsTo = array(
        'User', 'Lan'
    );
    public $hasOne = array('LanInvite');
    public $hasMany = array('LanSignupDay');
    public $validate = array(
        'user_id' => array(
            'validateUser' => array(
                'rule' => 'validateUser',
                'message' => 'Invalid user'
            ),
            'checkNotInLan' => array(
                'rule' => 'validateUserInLan',
                'message' => 'Invalid signup'
            )
        ),
        'lan_id' => array(
            'valid' => array(
                'rule' => 'validateLan',
                'message' => 'Invalid lan'
            )
        )
    );

    public function validateUserInLan($check) {
        if ($this->find('count', array('conditions' => array(
                        'LanSignup.user_id' => $check['user_id'],
                        'LanSignup.lan_id' => $this->data['LanSignup']['lan_id'],
                    )
                        )
                )
                == 0) {
            return true;
        }

        return false;
    }

    public function validateUser($check) {
        if ($this->User->find('count', array(
                    'conditions' => array(
                        'User.id' => $check['user_id']
                    )
                        )
                )
                == 1) {
            return true;
        }

        return false;
    }

    public function validateLan($check) {
        if ($this->Lan->find('count', array('conditions' => array(
                        'Lan.id' => $check['lan_id']
                    )
                        )
                )
                == 1) {
            return true;
        }

        return false;
    }

    public function getLanSignupsCrew($lan_id) {
        $this->Lan->id = $lan_id;
        if (!$this->Lan->exists()) {
            throw new NotFoundException('Lan not found with id #' . $lan_id);
        }

        $crew = $this->Lan->Crew->find('all', array(
            'conditions' => array(
                'Crew.lan_id' => $lan_id
            ),
            'recursive' => 0,
            'fields' => array(
                'Crew.user_id'
            )
                )
        );

        $crew_user_ids = array();
        foreach ($crew as $crew_member) {
            $crew_user_ids[] = $crew_member['Crew']['user_id'];
        }

        return $this->find('all', array(
                    'conditions' => array(
                        'LanSignup.lan_id' => $lan_id,
                        'LanSignup.user_id' => $crew_user_ids
                    ),
                    'recursive' => 2
                        )
        );
    }

    public function getLanSignupsCrewIds($lan_id) {
        $crew_user_data = $this->getLanSignupsCrew($lan_id);

        $lan_crew_user_ids = array();
        foreach ($crew_user_data as $crew) {
            $lan_crew_user_ids[] = $crew['User']['id'];
        }

        // Crew signed up for LAN
        $lan_signups_crew = $this->Lan->LanSignup->find('all', array(
            'conditions' => array(
                'LanSignup.lan_id' => $lan_id,
                'LanSignup.user_id' => $lan_crew_user_ids,
            ),
            'recursive' => -1,
                )
        );

        $lan_signups_id_crew = array();
        foreach ($lan_signups_crew as $lan_signup_crew) {
            $lan_signups_id_crew[] = $lan_signup_crew['LanSignup']['id'];
        }

        return $lan_signups_id_crew;
    }

}

?>
