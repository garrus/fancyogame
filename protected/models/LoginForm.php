<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;

	private $_account;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'Login name or email',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate()
	{
		if(!$this->hasErrors())
		{
			$password_match = false;
			$criteria = new CDbCriteria();
			$criteria->compare('login_name', $this->username, false, 'OR');
			$criteria->compare('email', $this->username, false, 'OR');
			$account=Account::model()->find($criteria);
			if ($account) {
				$password_match = $account->password == md5($this->password. $account->salt);
			}
			
			if(!$password_match)
				$this->addError('password','Incorrect username or password.');
			else
				$this->_account = $account;
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_account===null)
		{
			$this->authenticate();
		}
		if($this->_account)
		{
			Yii::app()->user->login($this->_account);
			return true;
		}
		else
			return false;
	}
}
