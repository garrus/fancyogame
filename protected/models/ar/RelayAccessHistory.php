<?php

/**
 * This is the model class for table "relay_access_history".
 *
 * The followings are the available columns in table 'relay_access_history':
 * @property string $id
 * @property string $relay_id
 * @property string $player_id
 * @property string $fleet_id
 * @property string $create_time
 *
 * The followings are the available model relations:
 * @property Fleet $fleet
 * @property Player $player
 * @property Relay $relay
 */
class RelayAccessHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RelayAccessHistory the static model class
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
		return 'relay_access_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('relay_id, player_id, fleet_id, create_time', 'required'),
			array('relay_id, player_id, fleet_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, relay_id, player_id, fleet_id, create_time', 'safe', 'on'=>'search'),
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
			'fleet' => array(self::BELONGS_TO, 'Fleet', 'fleet_id'),
			'player' => array(self::BELONGS_TO, 'Player', 'player_id'),
			'relay' => array(self::BELONGS_TO, 'Relay', 'relay_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'relay_id' => 'Relay',
			'player_id' => 'Player',
			'fleet_id' => 'Fleet',
			'create_time' => 'Create Time',
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
		$criteria->compare('relay_id',$this->relay_id,true);
		$criteria->compare('player_id',$this->player_id,true);
		$criteria->compare('fleet_id',$this->fleet_id,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}