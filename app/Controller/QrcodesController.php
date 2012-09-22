<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QrcodesController
 *
 * @author Jens
 */
class QrCodesController extends AppController {

//	public $components = array('RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
		$this->Auth->allow('add', 'generate');
    }

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		return true;
	}

	public function connect($qr_id, $user_id){

	}

	public function generate($count){
		$this->layout = 'print';

		if(! $count > 0){
			throw new InvalidArgumentException('Invalid parameter parsed');
		}

		$size = 3;

		$url = 'http://qr.kaywa.com/?s='.$size.'&amp;d=';

		$qr_codes = array();

		$current_count = 0;
		while($current_count < $count){
			$qr_codes[] = $url. '' . String::uuid();

			$current_count++;
		}

		$this->set(compact('qr_codes'));

		$this->set('per_page', 10);
		$this->set('per_line', 2);
	}


}

?>
