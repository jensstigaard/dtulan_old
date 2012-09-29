<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MarksController
 *
 * @author dengalepirat
 */
class MarksController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('api_add', 'api_view'));
    }
    
    public function api_add() {
        if ($this->request->is('post')) {
            if ($this->isJsonRequest()) {
                if ($this->Mark->save($this->request->data)) {
                    $this->set('success', true);
                    $this->set('data', array('message' => 'Marked user'));
                } else {
                    $this->set('success', false);
                    $this->set('data', array('message' => 'Unable to mark user'));
                }
                $this->set('_serialize', array('success', 'data'));
            } else {
                throw new BadRequestException;
            }
        } else {
            throw new MethodNotAllowedException;
        }
    }

    public function api_view($id) {
        if ($this->request->is('post')) {
            if ($this->isJsonRequest()) {
                $this->Mark->id = $id;
                if ($this->Mark->exists()) {
                    $this->Mark->read();
                    
                    $this->set('success', true);
                    $this->set('data', array(
                        'id' => $this->Mark->data['User']['id'],
                        'name' => $this->Mark->data['User']['email'],
                        'email' => $this->Mark->data['User']['email'],
                        'image_url' => 'http://www.gravatar.com/avatar/'.md5($this->Mark->data['User']['email_gravatar']).'?s=100&r=r',
                        'id_number' => $this->Mark->data['Ueer']['id_number']
                        )
                            );
                    $this->set('_serialize', array('success', 'data'));
                } else {
                    throw new InvalidArgumentException(__('Invalid mark'));
                }
            } else {
                throw new BadRequestException;
            }
        } else {
            throw new MethodNotAllowedException;
        }
    }

}

?>
