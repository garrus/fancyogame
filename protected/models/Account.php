<?php

/**
 * This is the model class for table "account".
 *
 * The followings are the available columns in table 'account':
 * @property string $id
 * @property string $email
 * @property string $login_name
 * @property string $password
 * @property string $salt
 * @property integer $is_activated
 * @property string $create_time
 * @property string $update_time
 * @property string $last_login_time
 * @property string $last_login_ip
 *
 * The followings are the available model relations:
 * @property Player[] $players
 */
class Account extends CActiveRecord implements IUserIdentity
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Account the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, login_name, password, salt', 'required'),
			array('is_activated', 'numerical', 'integerOnly'=>true),
			array('email, last_login_ip', 'length', 'max'=>64),
			array('login_name', 'length', 'max'=>45),
			array('password, salt', 'length', 'max'=>32),
			array('update_time, last_login_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, login_name, password, salt, is_activated, create_time, update_time, last_login_time, last_login_ip', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'players' => array(self::HAS_MANY, 'Player', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'login_name' => 'Login Name',
			'password' => 'Password',
			'salt' => 'Salt',
			'is_activated' => 'Is Activated',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'last_login_time' => 'Last Login Time',
			'last_login_ip' => 'Last Login Ip',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('login_name',$this->login_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('is_activated',$this->is_activated);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('last_login_ip',$this->last_login_ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public static function createNew($email, $login_name, $password){
		
		$model = new self;
		$model->email = $email;
		$model->login_name = $login_name;
		$model->salt = md5(microtime());
		$model->password = md5($password. $model->salt);
		if (!$model->save()) {
			throw new ModelError($model);
		}
		
		return $model;
	}
	
	/*
	 * (non-PHPdoc) @see IUserIdentity::authenticate()
	 */
	public function authenticate() {
		return true;
	}
	
	/*
	 * (non-PHPdoc) @see IUserIdentity::getIsAuthenticated()
	 */
	public function getIsAuthenticated() {
		return true;
	}
	
	/*
	 * (non-PHPdoc) @see IUserIdentity::getId()
	 */
	public function getId() {
		return $this->getAttribute('id');
	}
	
	/*
	 * (non-PHPdoc) @see IUserIdentity::getName()
	 */
	public function getName() {
		return $this->getAttribute('login_name');
	}
	
	/*
	 * (non-PHPdoc) @see IUserIdentity::getPersistentStates()
	 */
	public function getPersistentStates() {
		if ($this->last_login_time == '0000-00-00 00:00:00') {
			return array();
		} else {
			return array(
				'last_login_time' => $this->last_login_time,
				'last_login_ip' => $this->last_login_ip,
			);
		}
	}
	
	public static function updateLoginRecord($id, $login_ip, $login_time=null) {
		
		$model = self::model()->findByPk($id);
		if ($model) {
			$model->last_login_time = $login_time ?: new CDbExpression('CURRENT_TIMESTAMP');
			$model->last_login_ip = $login_ip;
			if (!$model->save()) {
				throw new ModelError($model);
			}
		}
		
	}

}