<?php

class SignupForm extends \CFormModel {
	
	public $email;
	public $login_name;
	public $password;
	public $confirm_password;
	
	public function rules(){
		return array(
			array('email,login_name,password,confirm_password', 'required'),
			array('email,login_name', 'filter', 'filter' => 'strtolower'),
			array('email', 'email'),
			array('email', 'length', 'max' => 64),
			array('login_name', 'length', 'min' => 3, 'max' => 45),
			array('login_name', 'match', 'pattern' => '/[^\w_]/', 'not' => true, 'message' => 'Login Name should only contains alphas, numbers and underline.'),
			array('password', 'length', 'min' => 3, 'max' => 45),
			array('password', 'match', 'pattern' => '/\s/', 'not' => true, 'message' => 'Password should not contain empty string.'),
			array('confirm_password', 'compare', 'compareAttribute' => 'password'),	
			array('login_name', 'unique', 'className' => 'Account', 'attributeName' => 'login_name'),
			array('email', 'unique', 'className' => 'Account', 'attributeName' => 'email'),
		);
	}
	
	
	public function afterValidate(){
		
		if (!$this->hasErrors('login_name')) {
			if (stripos($this->login_name, 'admin') !== false
			 || stripos($this->login_name, 'system') !== false
			 || stripos($this->login_name, 'sysop') !== false		
			) {
				$this->addError('login_name', 'Login name should not contain "admin", "system" or "sysops".');
			}
		}
	}

}
