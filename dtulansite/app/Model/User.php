<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User'
 *
 * @author Nigrea
 */
class User extends AppModel {
    
    public $validate = array(
        'first_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'First name is required'
            )
        ),
        'last_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Last name is required'
            )
        ),
        'email' => array(
            'required1' => array(
                'rule' => array('notEmpty'),
                'message' => 'Last name is required',
            ),
            'required2' => array(
                'rule' => array('email', true),        
                'message' => 'Please supply a valid email address.'               
            )
        ),
       
    );
}
?>
