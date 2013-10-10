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

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array(
			 'api_add',
			 'api_view',
			 'generate',
		));
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		return true;
	}

	public function api_add() {
		if (!$this->request->is('post')) {
			throw new BadRequestException('Bad request');
		}

		if (!isset($this->request->data['qr_code'])) {
			throw new BadFunctionCallException('Please supply a valid qr code');
		}

		if (!isset($this->request->data['user_id'])) {
			throw new BadFunctionCallException('Please supply a valid qr code');
		}

		$this->QrCode->User->id = $this->request->data['user_id'];

		if (!$this->QrCode->User->exists()) {
			throw new NotFoundException('User not found');
		}

		$data['QrCode']['id'] = $this->request->data['qr_code'];
		$data['QrCode']['user_id'] = $this->QrCode->User->id;

		if ($this->QrCode->save($data)) {
			$this->set('success', true);
			$this->set('data', array('message' => 'QR code is connected to user'));
		} else {
			$this->set('success', false);
			$this->set('data', array('message' => 'Something went seriously wrong'));
		}

		$this->set('_serialize', array('data', 'success'));
	}

	public function api_view($id) {
		if ($this->request->is('get')) {
			if ($this->isJsonRequest()) {
				$qr_code = $this->QrCode->find('first', array(
					 'conditions' => array(
						  'QrCode.id' => $id
					 ),
					 'fields' => array('QrCode.user_id'),
					 'recursive' => -1
						  )
				);
				if (count($qr_code['QrCode'])) {
					$this->set('success', true);
					$this->set('data', array('user_id' => $qr_code['QrCode']['user_id']));
				} else {
					$this->set('success', false);
					$this->set('data', array('message' => 'Unable to find a user for the given qr code'));
				}
				$this->set('_serialize', array('success', 'data'));
			} else {
				throw new BadRequestException('Invalid request from client');
			}
		} else {
			throw new MethodNotAllowedException('Not allowed');
		}
	}

	public function generate($count = 10) {
		$this->layout = 'print';

		$size = 3;

		$url = 'http://qr.kaywa.com/?s=' . $size . '&amp;d=';

		$qr_codes = array();

		$current_count = 0;
		while ($current_count < $count) {
			$qr_codes[] = $url . '' . String::uuid();

			$current_count++;
		}

		$this->set(compact('qr_codes'));

		$this->set('offset', 150);
		$this->set('per_page', 10);
		$this->set('per_line', 2);
	}

	public function admin_index() {
		$this->paginate = array(
			 'QrCode' => array(
				  'limit' => 12,
//				  'order' => 'time_created ASC',
				  'contain' => array(
						'User'
				  )
			 )
		);

		$this->set('qr_codes', $this->paginate('QrCode'));
	}

}

?>
