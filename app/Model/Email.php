<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Email
 *
 * @author Jens
 */
App::uses('CakeEmail', 'Network/Email');
App::uses('CakeEventListener', 'Event');

class Email extends Object implements CakeEventListener {

	public function implementedEvents() {
		return array(
			'Model.User.activationEmail' => 'sendActivationEmail'
		);
	}

	public function sendActivationEmail($event) {

		if (isset($event->data['user'])) {

			ini_set("SMTP", 'smtp.unoeuro.com');

			$email = new CakeEmail();
			$email->config('smtp');
			$email->emailFormat('html');
			$email->template('user_activate');
			$email->from(array('contact@dtu-lan.dk' => 'DTU LAN website'));
			$email->to($event->data['user']['email']);
			$email->subject('DTU LAN website - Activation');
			$email->viewVars(array('title_for_layout' => 'Activate user', 'activate_id' => $event->data['user']['id'], 'name' => $event->data['user']['name']));

			if ($email->send()) {
				// Awesome
			} else {
				$this->log('Activation email not send', 'user');
			}
		} else {
			$this->log('Activation email not send..', 'user');
		}
		return $event;
	}

}

?>
