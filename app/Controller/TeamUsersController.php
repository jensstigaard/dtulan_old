<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeamUsersController
 *
 * @author Jens
 */
class TeamUsersController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if (in_array($this->action, array('add'))) {
			return true;
		} elseif ($this->isAdmin()) {
			return true;
		}
		return false;
	}

	public function add($team_invite_id) {
		$this->TeamUser->Team->TeamInvite->id = $team_invite_id;

		if (!$this->TeamUser->Team->TeamInvite->exists()) {
			throw new NotFoundException('Team invite was not found');
		}

		$this->TeamUser->Team->TeamInvite->recursive = 2;
		$this->TeamUser->Team->TeamInvite->read();

		if ($this->TeamUser->Team->TeamInvite->data['TeamInvite']['user_id'] != $this->Auth->user('id')) {
			throw new UnauthorizedException('This invite does not belong to you :-)');
		}

		$team_id = $this->TeamUser->Team->TeamInvite->data['Team']['id'];
		$tournament_id = $this->TeamUser->Team->TeamInvite->data['Team']['Tournament']['id'];

		$this->request->data = array(
			 'TeamUser' => array(
				  'team_id' => $team_id,
				  'user_id' => $this->TeamUser->Team->TeamInvite->data['TeamInvite']['user_id'],
			 ),
			 'Team' => array(
				  'tournament_id' => $tournament_id
			 )
		);

		if ($this->TeamUser->save($this->request->data) && $this->TeamUser->Team->TeamInvite->delete()) {
			$this->Session->setFlash('You are now a part of the team', 'default', array('class' => 'message success'), 'good');
		} else {
			$this->Session->setFlash('FAILED to accept team invite', 'default', array(), 'bad');
		}

		$team = $this->TeamUser->Team->find('first', array(
			 'conditions' => array(
				  'Team.id' => $team_id
			 ),
			 'fields' => array(
				  'Team.slug'
			 ),
			 'contain' => array(
				  'Tournament' => array(
						'fields' => array(
							 'Tournament.slug'
						),
						'Lan' => array(
							 'fields' => array(
								  'Lan.slug'
							 )
						)
				  )
			 )
				  ));

		$this->redirect(array(
			 'controller' => 'teams',
			 'action' => 'view',
			 'lan_slug' => $team['Tournament']['Lan']['slug'],
			 'tournament_slug' => $team['Tournament']['slug'],
			 'team_slug' => $team['Team']['slug']
		));
	}

}

?>
