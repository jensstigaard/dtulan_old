<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QrcodesController
 *
 * @author Jens
 */
class QrCodesController extends AppController {

	public $components = array('RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('api_add', 'generate', 'api_view');
    }

    public function isAuthorized($user) {
        parent::isAuthorized($user);

        return true;
    }

    public function api_add() {
        if ($this->request->is('post')) {
            if ($this->isJsonRequest()) {
                $user = $this->QrCode->User->find('first', array(
                    'conditions' => array(
                        'email' => $this->request->data['QrCode']['email']
                    ),
                    'fiels' => array('User.id')
                )
                        );
                unset($this->request->data['QrCode']['email']);
                $this->request->data['QrCode']['user_id'];
                if (isset($user['User']['id']) && $this->QrCode->save($this->request->data)) {
                    $this->set('success', true);
                    $this->set('data', array('QR code is connected to user'));
                } else {
                    $this->set('success', false);
                    $this->set('data', array('message' => 'Unable to bind user to QR code'));
                }
                $this->set('_serialize', array('data', 'succes'));
            } else {
                throw new BadRequestException('Invalid request from client');
            }
        } else {
            throw new MethodNotAllowedException('Not allowed');
        }
    }

    public function api_view($id) {
        if ($this->request->is('get')) {
            if ($this->isJsonRequest()) {
                $qr_code = $this->QrCode->find('first', array(
                    'conditions' => array(
                        'QrCode.id' => $id
                    ),
                    'recursive' => 0
                        )
                );
                if (isset($qr_code['User'])) {
                    $this->set('success', true);
                    $this->set('data', array(
                        'id' => $qr_code['User']['id'], 
                        'name' => $qr_code['User']['name'], 
                        'email' => $qr_code['User']['email'], 
                        'image_url' => 'http://www.gravatar.com/avatar/'.md5($qr_code['User']['email_gravatar']).'?s=100&r=r', 
                        'id_number' => $qr_code['User']['id_number']));
                } else {
                    $this->set('success', false);
                    $this->set('data', array('message' => 'Unable to find a user for the given qr code'));
                }
                $this->set('_serialize', array('success', 'data'));
            } else {
                throw new BadRequestException('Invalid request from client');
            }
        } else {
            throw new MethodNotAllowedException('Not allowed');
        }
    }

    public function generate($count) {
        $this->layout = 'print';

        if (!$count > 0) {
            throw new InvalidArgumentException('Invalid parameter parsed');
        }

        $size = 3;

        $url = 'http://qr.kaywa.com/?s=' . $size . '&amp;d=';

        $qr_codes = array();

        $current_count = 0;
        while ($current_count < $count) {
            $qr_codes[] = $url . '' . String::uuid();

            $current_count++;
        }

        $this->set(compact('qr_codes'));

        $this->set('per_page', 10);
        $this->set('per_line', 2);
    }

}

?>
