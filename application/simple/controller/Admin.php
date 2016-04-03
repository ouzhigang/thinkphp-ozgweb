<?php
namespace app\simple\controller;

class Admin extends Base {
	
    public function login() {
		
		return $this->fetch("login");
    }
	
}
