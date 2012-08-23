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
	
}
?>

