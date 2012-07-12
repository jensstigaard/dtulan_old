<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersController
 *
 * @author Nigrea
 */
class UsersController extends AppController{
    
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
	}
	
    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }
    
    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
    }
    
    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }
    
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
    
    public function activate($id = null) {
		$this->User->setValidation('activate');
		
		$this->Registration->id = $id;
		if(!$this->Registration->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if($this->request->is('post')) {
			$registration = $this->Registration->find('first', array('conditions' => array('Registration.id' => $id)));
		
		$user = array();
		$user['User'] = $registration['Registration'];
		$user['User']['password'] = $this->request->data['password'];
			
		if($this->User->save($user) && $this->Registration->delete()) {
			/*
			 * Logs user in after successful activation
			 */
			$id = $this->User->id;
			$this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $id));
			$this->Auth->login($this->request->data['User']);			
			$this->Session->setFlash(__('User activated'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not activated'));	
		}
			
	}
	
	public function login() {
		if($this->request->is('post')) {
			if($this->Auth->login()) {
				$this->Session->setFlash(__('Logged in'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Invalid login'));
		}
		
	}
}
?>
