<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_username;

    private $_password;


    /**
     * Construtor
     *
     * @param string $username            
     * @param string $password            
     */
    public function __construct($username, $password) {

        $this->_username = $username;
        $this->_password = $password;
    }


    /**
     * Authenticates a user.
     *
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {

        $criteria = new CDbCriteria();
        $criteria->compare('login_name', $this->_username, false, 'OR');
        $criteria->compare('email', $this->_username, false, 'OR');
        $account = Account::model()->find($criteria);
        if ($account) {
            if ($account->password == md5($this->_password . $account->salt)) {

                $this->setState('__id', $account->id);
                $this->setState('__name', $account->login_name);
                
                if ($account->last_login_ip) {
                    $this->setState('last_login_time', $account->last_login_time);
                    $this->setState('last_login_ip', $account->last_login_ip);
                }
                
                $this->errorCode = self::ERROR_NONE;
                return true;
            }
            
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        return false;
    }


    /**
     * (non-PHPdoc)
     *
     * @see CUserIdentity::getId()
     */
    public function getId() {

        return $this->getState('__id', null);
    }


    /**
     * (non-PHPdoc)
     *
     * @see CUserIdentity::getName()
     */
    public function getName() {

        return $this->getState('__name', null);
    }

}