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
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('CakeEventListener', 'Event');

class Email extends AppModel implements CakeEventListener {

	public function implementedEvents() {
		return array(
			 'Model.User.activationEmail' => 'sendActivationEmail',
			 'Model.Lan.sendSubscriptionEmail' => 'sendSubscriptionEmail'
		);
	}

	public function sendActivationEmail($event) {

		if (!isset($event->data['user'])) {
			$this->log('Activation email not send.. Wrong data received', 'user');
			return $event->data + array('success' => false);
		}

		$email = new CakeEmail();
		$email->config('smtp')
				  ->emailFormat('html')
				  ->template('user_activate')
				  ->from(array('contact@dtu-lan.dk' => 'DTU LAN website'))
				  ->to($event->data['user']['email'])
				  ->subject('DTU LAN website - Activation')
				  ->viewVars(array(
						'title_for_layout' => 'Activate user',
						'activate_id' => $event->data['user']['id'],
						'name' => $event->data['user']['name']
				  ));

		if (!$email->send()) {
			$this->log('Activation email not send', 'email');
			return $event->data + array('success' => false);
		}

		return $event->data + array('success' => true);
	}

	public function sendSubscriptionEmail($event) {

		if (!isset($event->data['user'])) {
			$this->log('Subscription email not send.. Wrong data received', 'email');
			return $event->data + array('success' => false);
		}

		$email = new CakeEmail();
		$email->config('smtp')
				  ->emailFormat('html')
				  ->template('lan_subscription')
				  ->from(array('contact@dtu-lan.dk' => 'DTU LAN website'))
				  ->to($event->data['user']['email'])
				  ->subject('Lan event announcement')
				  ->viewVars(array(
						'title_for_layout' => 'Lan event announcement',
						'data' => $event->data,
				  ));

		if (!$email->send()) {
			$this->log('Subscription email not send', 'email');
			return $event->data + array('success' => false);
		}

		return $event->data + array('success' => true);
	}

}