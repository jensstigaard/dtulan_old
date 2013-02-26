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

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if (in_array($this->action, array('view', 'add', 'delete')) || $this->isAdmin()) {
			return true;
		}
		return false;
	}

	public function add($lan_slug, $tournament_slug) {

		$this->Team->Tournament->id = $this->Team->Tournament->getIdByLanSlugAndTournamentSlug($lan_slug, $tournament_slug);

		if (!$this->Team->Tournament->exists()) {
			throw new NotFoundException('Team not found');
		}

		if ($this->request->is('post')) {

			$this->request->data['Team']['tournament_id'] = $this->Team->Tournament->id;

			$this->request->data['TeamUser'] = array(
				 0 => array(
					  'user_id' => $this->Auth->user('id'),
					  'is_leader' => 1,
//					  'Team' => array(
//							'tournament_id' => $tournament_id
//					  )
				 )
			);


			if ($this->Team->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Your team has been created', 'default', array('class' => 'message success'), 'good');
				$this->redirect(array('controller' => 'teams', 'action' => 'view', $this->Team->getInsertID()));
			} else {
				$errors = $this->Team->invalidFields();

				if (isset($errors['TeamUser'][0]['user_id'][0])) {
					$this->Session->setFlash($errors['TeamUser'][0]['user_id'][0], 'default', array(), 'bad');
				} else {
					$this->Session->setFlash('Unable to create your team', 'default', array(), 'bad');
				}
			}
		}



		$this->set('tournament', $this->Team->Tournament->read());
	}

	public function view($id = null) {

		$this->Team->id = $id;

		if (!$this->Team->exists()) {
			throw new NotFoundException('Team not found');
		}

		$this->Team->recursive = 2;

		$this->set('team', $this->Team->find('first', array(
						'conditions' => array(
							 'Team.id' => $this->Team->id
						),
						'contain' => array(
							 'Tournament' => array(
								  'fields' => array(
										'slug',
										'title'
								  ),
								  'Lan' => array(
										'fields' => array(
											 'slug'
										)
								  )
							 ),
							 'TeamInvite' => array(
								  'User' => array(
										'fields' => array(
											 'id',
											 'name',
											 'gamertag',
										)
								  )
							 ),
							 'TeamUser' => array(
								  'User' => array(
										'fields' => array(
											 'id',
											 'name',
											 'gamertag',
										)
								  )
							 )
						)
				  )));

		$this->set('is_leader', $this->Auth->loggedIn() && $this->Team->isLeader($this->Auth->user('id')));

		$this->set('users', $this->Team->getInviteableUsers($id));
	}

	public function delete($id) {
		$this->Team->id = $id;

		if (!$this->Team->exists()) {
			throw new NotFoundException('Team not found');
		}

		$team = $this->Team->find('first', array(
			 'conditions' => array(
				  'Team.id' => $this->Team->id,
			 ),
			 'contain' => array(
				  'Tournament' => array(
						'fields' => array(
							 'slug'
						),
						'Lan' => array(
							 'fields' => array(
								  'slug'
							 )
						)
				  )
			 )
				  ));

		if (!$this->Team->isLeader($this->Auth->user('id'))) {
			throw new UnauthorizedException('You are not allowed to do this');
		}

		if ($this->Team->delete()) {
			$this->Session->setFlash('Team deleted', 'default', array(), 'bad');
		} else {
			$this->Session->setFlash('Unable to delete your team', 'default', array('class' => 'message success'), 'good');
		}

		$this->redirect(array(
			 'controller' => 'tournaments',
			 'action' => 'view',
			 'lan_slug' => $team['Tournament']['Lan']['slug'],
			 'tournament_slug' => $team['Tournament']['slug']
		));
	}

}