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

		if(!$this->Team->exists()){
			throw new NotFoundException('Team not found');
		}

		$this->Team->recursive = 2;
		$this->set('team', $this->Team->read());
	}

	public function invite($id = null) {

		$this->Team->id = $id;

		if(!$this->Team->exists()){
			throw new NotFoundException('Team not found');
		}

		if ($this->request->is('post')) {

			if ($this->Team->save($this->request->data)) {
				$this->Session->setFlash('Your invites has been sent.');
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to send your invites.');
			}
		}

		$this->Team->recursive = 2;
		$team = $this->Team->read();

		$user_ids = array();
		foreach ($team['Invite'] as $user) {
			$user_ids[] = $user['id'];
		}

		foreach ($team['TeamUser'] as $user) {
			$user_ids[] = $user['user_id'];
		}

		$users = $this->Team->Invite->find('list', array('conditions' => array(
				'NOT' => array(
					'Invite.id' => $user_ids,
				),
			//	'Lan.id'
			)
				)
		);

		$this->set(compact('team', 'users'));
	}

}

?>
