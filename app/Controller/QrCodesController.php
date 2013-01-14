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

	public $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('api_add', 'generate', 'api_view');
	}

	public function isAuthorized($user) {
		parent::isAuthorized($user);

		return true;
	}

	public function api_add() {
		if ($this->request->is('post')) {
			if ($this->isJsonRequest()) {
				if (!isset($this->request->data['QrCode']) || !isset($this->request->data['QrCode']['qr_code'])) {
					throw new BadFunctionCallException('Please supply a valid qr code');
				}

				$conditions = array();
				if (isset($this->request->data['QrCode']['email'])) {
					$conditions['conditions']['email'] = $this->request->data['QrCode']['email'];
				} else if (isset($this->request->data['QrCode']['id_number'])) {
					$conditions['conditions']['id_number'] = $this->request->data['QrCode']['id_number'];
				} else {
					throw new BadFunctionCallException('Please supply either a E-mail or ID number');
				}

				$conditions['fields'] = array('User.id');
				$user = $this->QrCode->User->find('first', $conditions);
				if (count($user)) {
					$data = array();
					$data['QrCode']['id'] = $this->request->data['QrCode']['qr_code'];
					$data['QrCode']['user_id'] = $user['User']['id'];
					try {
						if (isset($user['User']['id']) && $this->QrCode->save($data)) {
							$this->set('success', true);
							$this->set('data', array('message' => 'QR code is connected to user'));
						} else {
							$user = $this->QrCode->User->find('first', array(
								'conditions' => array('user_id' => $user['User']['id'])
									)
							);
							if (count($user)) {
								$this->set('success', false);
								$this->set('data', array('message' => 'User already registered'));
							} else {
								$qr_code = $this->QrCode->find('first', array('conditions' => array('id' => $this->request->data['QrCode']['qr_code'])));
								if (count($qr_code)) {
									$this->set('success', false);
									$this->set('data', array('message' => 'QR code already in use'));
								} else {
									$this->set('success', false);
									$this->set('data', array('message' => 'Something went seriously wrong'));
								}
							}
						}
					} catch (PDOException $e) {
						$user = $this->QrCode->User->find('first', array(
							'conditions' => array('User.id' => $user['User']['id'])
								)
						);
						if (count($user)) {
							$this->set('success', false);
							$this->set('data', array('message' => 'User already registered'));
						} else {
							$qr_code = $this->QrCode->find('first', array('conditions' => array('QrCode.id' => $this->request->data['QrCode']['qr_code'])));
							if (count($qr_code)) {
								$this->set('success', false);
								$this->set('data', array('message' => 'QR code already in use'));
							} else {
								$this->set('success', false);
								$this->set('data', array('message' => 'Something went seriously wrong'));
							}
						}
					}
				} else {
					$this->set('success', false);
					$this->set('data', array('message' => 'Unable to find user with given information'));
				}
				$this->set('_serialize', array('data', 'success'));
			} else {
				throw new BadRequestException('Invalid request from client');
			}
		} else {
			throw new MethodNotAllowedException('Not allowed');
		}
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

	public function generate($count) {
		$this->layout = 'print';

		if (!$count > 0) {
			throw new InvalidArgumentException('Invalid parameter parsed');
		}

		$size = 3;

		$url = 'http://qr.kaywa.com/?s=' . $size . '&amp;d=';

		$qr_codes = array();

		$current_count = 0;
		while ($current_count < $count) {
			$qr_codes[] = $url . '' . String::uuid();

			$current_count++;
		}

		$this->set(compact('qr_codes'));

		$this->set('offset', 10);
		$this->set('per_page', 10);
		$this->set('per_line', 2);
	}

}

?>
