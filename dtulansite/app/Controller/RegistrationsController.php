<?php
 App::uses('CakeEmail', 'Network/Email'); 
 
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
        $this->set('registration', $this->Registration->read(null, $id));
    }
    
    public function viewAll (){
        $this->set('registrations', $this->Registration->find('all'));
    }

    public function add() {
        if ($this->request->is('post')) {
            
            $this->request->data['Registration']['creation_time'] = date('Y-m-d H:i:s');

            $this->Registration->create();
            if ($this->Registration->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
				
				$msg = "<b>This is an automated activation mail, from the DTU-Lan crew<b> <br />
						Someone used this mail as there registration mail for the DTU-Lan homepage <br />
						And registered under the name ".$this->request->data['Registration']['first_name'].
						" ".$this->request->data['Registration']['last_name'].
						"If you wish to activate the account now, use the following link, and choose a password: <br />".
						$this->Html->link('http://dtu-lan.dk/activate/'.$this->request->data['Registration']['id'],'http://dtu-lan.dk/activate/'.$this->request->data['Registration']['id'])
						;
				
				$email = new CakeEmail();
				$email->from(array('admin@DTU-Lan.dk' => 'DTU-Lan'));
				$email->to($this->request->data['Registration']['email']);
				$email->subject('DTU-Lan Activation');
				$email->send($msg);
				
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function edit($id = null) {
        $this->Registration->id = $id;
        $this->set('registration', $this->Registration->read(null, $id));         
        if (!$this->Registration->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Registration->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'viewAll'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Registration->read(null, $id);
            //unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        $this->Registration->id = $id;
        if (!$this->Registration->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
		$this->Registration->delete();
    }
    
}

?>
