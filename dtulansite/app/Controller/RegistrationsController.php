<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegistrationController
 *
 * @author Nigrea
 **/

class RegistrationsController extends AppController{

    public function index() {
        $this->Registration->recursive = 0;
        $this->set('registrations', $this->paginate());
    }

    public function view($id = null) {
        $this->Registration->id = $id;
        if (!$this->Registration->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        if ($this->request->is('post')) {

			// The studynumber has to be saved as the field id_number in the database
            $this->request->data['Registration']['id_number'] =  str_replace('s','', $this->request->data['Registration']['study_number']);

			// We also need to set a creation time for the request
            $this->request->data['Registration']['creation_time'] = date('Y-m-d H:i:s');

            $this->Registration->create();
            if ($this->Registration->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been registered. An activation mail is sent to your email.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function edit($id = null) {
        $this->Registration->id = $id;
        if (!$this->Registration->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Registration->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Registration->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Registration->id = $id;
        if (!$this->Registration->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->Registration->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}

?>
