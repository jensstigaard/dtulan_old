 <?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TournamentsController extends AppController {

	public function view($id=null) {

		$this->Tournament->id = $id;

		
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

