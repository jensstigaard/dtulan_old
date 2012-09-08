 <?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TournamentsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		if ($this->isAdmin($user)) {
			return true;
		}
		return false;
	}

	public function view($id=null) {

		$this->Tournament->id = $id;

		$this->Tournament->recursive = 2;
		$this->set('tournament', $this->Tournament->read());
	}
	public function add() {

		if ($this->request->is('post')) {

			if ($this->Tournament->save($this->request->data)) {
				$this->Session->setFlash('Your Tournament has been created.');
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to create your tournament.');
			}
		}

		$this->set('lans', $this->Tournament->Lan->find('list'));
		$this->set('games', $this->Tournament->Game->find('list'));



	}

}
?>

