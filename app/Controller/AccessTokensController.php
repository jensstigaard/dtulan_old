<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccessTokensController
 *
 * @author Jens
 */
class AccessTokensController extends AppController {

    public function beforeFilter() {
        
    }

    public function api_add() {
        if ($this->request->is('post')) {
            if ($this->isJsonRequest()) {
                if ($this->Auth->login() && $this->isAdmin()) {
                    if ($this->AccessToken->save(array(
                                'AccessToken' => array(
                                    'user_id' => $this->Auth->user('id'),
                                    'time_expiration' => date('Y-m-d H:i:s', strtotime('+1 week'))
                                )
                                    )
                            )
                    ) {
                        $this->set('success', true);
                        $this->set('data', array(
                            'id' => $this->AccessToken->getLatestInsertId(),
                                )
                        );
                    } else {
                        $this->set('success', false);
                        $this->set('data', array(
                            'message' => 'Unable to create access token'
                                )
                        );
                    }
                } else {
                    $this->set('success', false);
                    $this->set('data', array('data' => array(
                            'message' => 'Invalid email/password combination'
                        )
                            )
                    );
                }
                $this->set('_serialize', array('success', 'data'));
            } else {
                $this->response->statusCode(406);
            }
        } else {
            $this->response->statusCode(405);
        }
    }

    public function api_delete() {
        if ($this->request->is('delete')) {
            if ($this->isJsonRequest()) {
                if ($this->Auth->login() && $this->isAdmin()) {
                    if ($this->AccessToken->updateAll()) {
                        $this->set('success', true);
                        $this->set('data', array('message' => 'Successfully logout'));
                    } else {
                        $this->set('success', false);
                        $this->set('data', array('message' => __('Unable to logout')));
                    }
                    $this->set('_serialize', array('data', 'success'));
                } else {
                    $this->response->statusCode(401);
                }
            } else {
                $this->response->statusCode(406);
            }
        } else {
            $this->response->statusCode(405);
        }
    }

}

?>
