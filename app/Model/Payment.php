<?php

/**
 * Description of Payment
 *
 * @author superkatten
 */
class Payment extends AppModel {
	public $belongsTo = array('User', 'Crew');
	
	public $validate = array(
		'amount' => array(
			'required1' => array(
				'rule' => array('notEmpty', 'numeric'),
				'message' => 'Amount is invalid'
			),
			'required2' => array(
				'rule' => 'positiveInteger',
				'message' => 'Amount is invalid'
			),
		)
	);
	
	public function positiveInteger($check){
		if(is_numeric($check['amount']) && $check['amount'] > 0 && $check['amount'] <= 999){
			return true;
		}
		else{
			return false;
		}
	}
	
	

}

?>
