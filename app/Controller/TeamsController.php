<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeamController
 *
 * @author Superkatten
 */
class TeamsController extends AppController {

	//put your code here
	public function add($tournament_id = null) {

		if ($tournament_id == null) {
			throw new NotFoundException('Category not found');
		}

		$this->Team->Tournament->id = $tournament_id;

		if (!$this->Team->Tournament->exists()) {
			throw new NotFoundException('Category not found');
		}

		if ($this->request->is('post')) {

			$user = $this->Auth->user();

			$this->request->data['Team']['user_id'] = $user['id'];
			$this->request->data['Team']['tournament_id'] = $tournament_id;

			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash('Your Team has been created.');
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to create your Team.');
			}
		}

		$this->set('tournament', $this->Team->Tournament->read());
	}

	public function view($id = null) {
		$this->Team->id = $id;

		$this->Team->recursive = 2;
		$this->set('team', $this->Team->read());
	}

	public function invite($id = null) {
		
		if ($this->request->is('post')) {
			
			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash('Your invites has been sent.');
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to send your invites.');
			}
		}
		
		$this->Team->id = $id;

		$team = $this->Team->read();

		$user_ids = array();
		foreach ($team['User'] as $user) {	
			$user_ids[] = $user['id'];
		}

		$users = $this->Team->User->find('list', array('conditions' => array(
				'NOT' => array(
					'User.id' => $user_ids
				),
			//	'Lan.id' 
			)
				)
		);

		$this->set(compact('team', 'users'));
	}

}

?>
