<?php

class LansController extends AppController {

    public $components = array('RequestHandler');
    public $helpers = array('Html', 'Form', 'Js');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('view');
    }

    public function isAuthorized($user) {
        parent::isAuthorized($user);

        if ($this->isAdmin($user)) {
            return true;
        }
        return false;
    }

    public function index() {
        $this->Lan->recursive = 1;
        $this->set('lans', $this->Lan->find('all'));
    }

    public function view($slug) {

        $cond = array('Lan.slug' => $slug);

        if ($this->Auth->loggedIn()) {
            $user = $this->Auth->user();

            if (!$this->Lan->LanSignup->User->isAdmin($user)) {
                $cond['published'] = 1;
            }
        } else {
            $cond['published'] = 1;
        }

        $lan = $this->Lan->find('first', array('conditions' => $cond));

        if (!$lan) {
            throw new NotFoundException('No LAN found');
        }

        $this->Lan->id = $lan['Lan']['id'];
        if (!$this->Lan->exists()) {
            throw new NotFoundException('LAN not found with id ' . $this->Lan->id);
        }

        $title_for_layout = 'Lan &bull; ' . $lan['Lan']['title'];

        $this->set(compact('lan', 'title_for_layout'));
        $this->set('count_tournaments', $this->Lan->countTournaments());
        $this->set('count_lan_signups', $this->Lan->countSignups());
        $this->set('count_lan_signups_guests', $this->Lan->countGuests());
        $this->set('lan_days', $this->Lan->getLanDays());
        $this->set('lan_invites', $this->Lan->getLanInvites());
        $this->set('total_pizzas', $this->Lan->PizzaWave->getTotalPizzasByLan($this->Lan->id));
        $this->set('total_pizza_orders', $this->Lan->PizzaWave->getTotalPizzaOrdersByLan($this->Lan->id));
        $this->set('food_orders_count', $this->Lan->getCountFoodOrders());
        $this->set('food_orders_total', $this->Lan->getFoodOrdersTotal());
        
        if ($lan['Lan']['sign_up_open'] && isset($user)) {
            if ($user['type'] == 'student') {
                if (!$this->Lan->isUserAttending($this->Lan->id, $user['id'])) {
                    $this->set('is_not_attending', 1);
                } else {
                    if ($this->request->is('post')) {
                        $this->request->data['LanInvite']['lan_id'] = $this->Lan->id;
                        $this->request->data['LanInvite']['user_student_id'] = $user['id'];
                        $this->request->data['LanInvite']['time_invited'] = date('Y-m-d H:i:s');

                        if ($this->Lan->LanInvite->save($this->request->data)) {
                            $this->Session->setFlash('Your invite has been sent', 'default', array('class' => 'message success'), 'good');
                        } else {
                            $this->Session->setFlash('Unable to send your invite', 'default', array(), 'bad');
                        }
                    }

                    $user_guests = $this->Lan->getInviteableUsers($this->Auth->user('id'));
                    $this->set(compact('user_guests'));
                }
            }
        }
    }

    public function add() {
        if ($this->request->is('post')) {

            $this->request->data['Lan']['slug'] = $this->Lan->stringToSlug($this->request->data['Lan']['title']);
            $this->request->data['LanDay'] = $this->Lan->getLanDaysByTime($this->request->data['Lan']['time_start'], $this->request->data['Lan']['time_end']);
            if ($this->Lan->saveAssociated($this->request->data)) {
                $this->Session->setFlash('Your Lan has been saved.', 'default', array('class' => 'message success'), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add your lan.', 'default', array(), 'bad');
            }
        }
    }

    public function edit($id = null) {
        $this->Lan->id = $id;
        if (!$this->Lan->exists()) {
            throw new NotFoundException(__('Invalid Lan'));
        }

        if ($this->request->is('get')) {
            $this->request->data = $this->Lan->read();
        } else {
            $this->request->data['Lan']['slug'] = $this->Lan->stringToSlug($this->request->data['Lan']['title']);

            if ($this->Lan->save($this->request->data)) {
                $this->Session->setFlash(__('The LAN has been saved'), 'default', array('class' => 'message success'), 'good');
//				$this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Lan could not be saved. Please, try again.'), 'default', array(), 'bad');
            }
        }
    }

    public function openForSignup($id = null) {

        $this->Lan->id = $id;
        if (!$this->Lan->exists()) {
            throw new NotFoundException(__('Invalid Lan'));
        }

        if (!$this->request->is('post')) {
            throw new BadRequestException('Bad request');
        }

        $this->request->data['Lan']['sign_up_open'] = 1;

        if ($this->Lan->save($this->request->data)) {
            $this->Session->setFlash(__('The LAN has been opened for signups'), 'default', array('class' => 'message success'), 'good');
        } else {
            $this->Session->setFlash(__('The Lan could not be saved. Please try again'), 'default', array(), 'bad');
        }
        $this->redirect($this->referer());
    }

    public function view_print($slug) {
        $this->layout = 'print';
        $cond = array('Lan.slug' => $slug);

        $lan = $this->Lan->find('first', array('conditions' => $cond));


        if (!$lan) {
            throw new NotFoundException('No LAN found');
        }

        $lan_id = $lan['Lan']['id'];

        $title_for_layout = 'Lan &bull; ' . $lan['Lan']['title'];

        $this->set(compact('lan', 'title_for_layout'));


        $this->set('lan_days', $this->Lan->LanDay->find('all', array(
                    'conditions' => array(
                        'LanDay.lan_id' => $lan_id
                    ),
                    'order' => array(
                        'LanDay.date ASC',
                    )
                        )
                )
        );

        $this->Lan->LanInvite->recursive = 2;

        $this->set('lan_invites', $this->Lan->LanInvite->find('all', array(
                    'conditions' => array(
                        'LanInvite.lan_id' => $lan_id,
                        'LanInvite.accepted' => 0
                    )
                        )
                )
        );

        $this->set('count_lan_signups', $this->Lan->LanSignup->countTotalInLan($lan_id));
        $this->set('count_lan_signups_guests', $this->Lan->LanInvite->countGuestsInLan($lan_id));

        // Tournaments signed up for LAN
        $conditions_tournaments = array(
            'Tournament.lan_id' => $lan_id,
        );

        $this->Lan->Tournament->recursive = 2;
        $tournaments = $this->Lan->Tournament->find('all', array(
            'conditions' => $conditions_tournaments
                ));

        $this->set(compact('tournaments'));

        // Users signed up for LAN
        $this->Lan->LanSignup->recursive = 2;
        $this->Lan->LanSignup->unbindModel(array(
            'belongsTo' => array(
                'Lan'
            ),
            'hasOne' => array(
                'LanInvite'
            ),
            'hasMany' => array(
//				'LanSignupDay'
            )
                )
        );
        $this->Lan->LanSignup->User->unbindModel(array(
            'hasOne' => array(
                'UserPasswordTicket'
            ),
            'hasMany' => array(
                'LanSignup',
                'LanInvite',
                'LanInviteSent',
                'Payment',
                'PizzaOrder',
                'TeamInvite',
                'TeamUser'
            )
                )
        );

        $this->Lan->Crew->recursive = 0;
        $lan_crews = $this->Lan->getCrew();

        $lan_crew_ids = array();
        foreach ($lan_crews as $crew) {
            $lan_crew_ids[] = $crew['Crew']['user_id'];
        }

        // Crew signed up for LAN
        $lan_signups_crew = $this->Lan->LanSignup->find('all', array(
            'conditions' => array(
                'LanSignup.lan_id' => $lan_id,
                'LanSignup.user_id' => $lan_crew_ids,
            ),
            'order' => array(
                'User.name'
            )
                )
        );

        $lan_signups_id_crew = array();

        foreach ($lan_signups_crew as $lan_signup_crew) {
            $lan_signups_id_crew[] = $lan_signup_crew['LanSignup']['id'];
        }


        $lan_signups = $this->Lan->LanSignup->find('all', array(
            'conditions' => array(
                'LanSignup.lan_id' => $lan_id,
                'NOT' => array(
                    'LanSignup.id' => $lan_signups_id_crew
                )
            ),
            'order' => array(
                array('User.name' => 'asc')
            )
                )
        );

        $this->set(compact('lan_signups', 'lan_signups_crew'));
    }

}
