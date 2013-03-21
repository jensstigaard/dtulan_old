<?php

class LanViewTestCase extends CakeTestCase {

	function startTest() {
		$Controller = new Controller();
		$this->View = new View($Controller);
		$this->View->layout = null;
		$this->View->viewPath = 'lans';
	}

	function testPostInstance() {
		$this->assertTrue(is_a($this->View, 'View'));
	}

}