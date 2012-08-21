<?php

class LansController extends AppController {

	public $helpers = array('Html', 'Form');

	public function index() {
		$this->set('lans', $this->Lan->find('all'));
	}

	public function viewPastLans() {
		$currentTime = date('Y-m-d H:i:s');
		$data = $this->Lan->find('all', array(
			'conditions' => array(
				'Lan.published' => 1,
				'Lan.time_end <' => $currentTime
				)));

		$this->set('lans', $data);
		$this->view = 'index';
	}

	public function viewCurrentLans() {
		$currentTime = date('Y-m-d H:i:s');
		$data = $this->Lan->find('all', array(
			'conditions' => array(
				'Lan.time_end >' => $currentTime,
				'Lan.time_start <' => $currentTime
				)));
		$this->set('lans', $data);
		$this->view = 'index';
	}

	public function viewFutureLans() {
		$currentTime = date('Y-m-d H:i:s');
		$data = $this->Lan->find('all', array(
			'conditions' => array(
				'Lan.time_start >' => $currentTime
				)));
		$this->set('lans', $data);
		$this->view = 'index';
	}

	public function add() {
		if ($this->request->is('post')) {
			if ($this->Lan->save($this->request->data)) {
				$this->Session->setFlash('Your Lan has been saved.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your lan.');
			}
		}
	}

	public function edit($id = null) {
		$this->Lan->id = $id;
        if (!$this->Lan->exists()) {
            throw new NotFoundException(__('Invalid Lan'));
        }

		$this->set('lan', $this->Lan->read(null, $id));

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Lan->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Lan could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Lan->read(null, $id);
        }
	}

	public function view($id = null){
		$this->Lan->id = $id;

		$this->Lan->recursive = 1;
		$this->set('lan', $this->Lan->read());
	}


	public function lookup($id = null){
		$this->Lan->id = $id;

		if(!$this->Lan->exists()){
			throw new NotFoundException('Lan not found');
		}

		$this->Lan->recursive = -1;
		//$this->set('lan', $this->Lan->read());

		$this->Lan->Behaviors->attach('Containable');
		$this->Lan->contain(array('LanSignup' => array('User')));
		$this->set('lan', $this->Lan->read());
	}

}

?>
