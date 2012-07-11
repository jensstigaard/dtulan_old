<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Registration
 *
 * @author Nigrea
 */
class Registration extends AppModel{
   
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
                'message' => 'Email name is required',
            ),
            'required2' => array(
                'rule' => array('email', true),        
                'message' => 'Please supply a valid email address.'               
            ),
            'required3' => array(
                'rule' => 'isUnique',
                'message' => 'This Email has already been taken.'    
            )
        ),
       'study_number' => array(
           'required' => array(
                'rule' => 'validateStudynumber',
                'message' => 'Not a valid study number'
            )
       ),
       
       'type' => array(
            'valid' => array(
                'rule' => array('inList', array('guest', 'student')),
                'message' => 'Please enter a valid type',
                'allowEmpty' => false
            )
        )
       
    );
   
   function validateStudynumber($check){
       
       if($this->data['Registration']['type'] == 'student')
       {
           return preg_match("/^s[0-9]{6}$/", $this -> data['Registration']['study_number']);
       }
       else{
           $this->data['Registration']['study_number'] = '';
           return true;
       }
       
   }
}

?>
